<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\NDV01CharacState;
use App\Models\NDV01Charac;
use App\Models\NDV02InvenWhereTable;
use App\Models\WeaponChangeConfig;
use App\Models\WeaponType;
use App\Models\WeaponImage;
use App\Models\WeaponChangeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WeaponController extends Controller
{
    /**
     * Kiểm tra cấu hình và điều kiện trước khi cho phép thay đổi vũ khí
     */
    private function checkConfigAndRequirements($character)
    {
        // Lấy cấu hình chung về thay đổi vũ khí
        $config = WeaponChangeConfig::first();
        
        // Kiểm tra xem tính năng có bật không
        if (!$config || !$config->is_enabled) {
            return [
                'success' => false,
                'message' => $config ? $config->maintenance_message : 'Tính năng thay đổi vũ khí đang bảo trì.'
            ];
        }
        
        // Kiểm tra cấp độ tối thiểu
        if ($character->inner_level < $config->min_level_required) {
            return [
                'success' => false,
                'message' => "Nhân vật phải đạt cấp {$config->min_level_required} trở lên để sử dụng tính năng này."
            ];
        }
        
        return [
            'success' => true,
            'change_fee' => $config->change_fee
        ];
    }

    /**
     * Xác định loại vũ khí dựa vào item_id sử dụng database
     */
    private function getWeaponTypeFromDB($itemId)
    {
        $weaponType = WeaponType::where('min_id', '<=', $itemId)
            ->where('max_id', '>=', $itemId)
            ->first();
            
        return $weaponType;
    }

    /**
     * Lấy tên vũ khí từ database thay vì hardcode
     */
    private function getWeaponNameFromDB($itemId)
    {
        // Xác định tên cơ bản của vũ khí (Kiếm, Đao, Bổng, v.v.)
        $baseWeaponName = $this->getWeaponBaseName($itemId);
        
        // Lấy thông tin loại vũ khí
        $weaponType = $this->getWeaponTypeFromDB($itemId);
        if ($weaponType) {
            // Trả về tên định dạng "[TênCơBản] [TênLoạiVũKhí]" 
            return "{$baseWeaponName} {$weaponType->name}";
        }
        
        // Fallback sử dụng phương thức cũ nếu không tìm thấy trong database
        // Với tên vũ khí từ DB không tìm thấy
        return $this->getWeaponName($itemId);
    }
    
    /**
     * Lấy tên cơ bản của vũ khí (Kiếm, Đao, Bổng, v.v.) dựa vào item_id
     */
    private function getWeaponBaseName($itemId)
    {
        // Xác định index của vũ khí trong nhóm (0-9)
        $weaponType = $this->getWeaponTypeFromDB($itemId);
        if (!$weaponType) {
            return '';
        }
        
        $index = ($itemId - $weaponType->min_id) % 10;
        
        // Danh sách tên cơ bản của vũ khí
        $baseNames = [
            0 => 'Kiếm',
            1 => 'Đao',
            2 => 'Bổng',
            3 => 'Quyền',
            4 => 'Đoản Kiếm',
            5 => 'Phủ',
            6 => 'Song Hoàn',
            7 => 'Thương',
            8 => 'Côn',
            9 => 'Cương'
        ];
        
        return $baseNames[$index] ?? "Vũ khí #$index";
    }
    
    /**
     * Lưu lịch sử thay đổi vũ khí
     */
    private function logWeaponChange($userId, $characterId, $characterName, $oldWeaponId, $newWeaponId, $cost)
    {
        WeaponChangeLog::create([
            'user_id' => $userId,
            'character_id' => $characterId,
            'character_name' => $characterName,
            'old_weapon_id' => $oldWeaponId,
            'new_weapon_id' => $newWeaponId,
            'cost' => $cost
        ]);
    }

    /**
     * Kiểm tra trạng thái online/offline của nhân vật
     * Sử dụng state trong ND_V01_Charac thay vì member_class
     */
    private function checkCharacterState($character)
    {
        // Lấy thông tin state của nhân vật từ ND_V01_Charac
        $characterState = DB::connection('cuuamsql2')
            ->table('ND_V01_Charac')
            ->where('unique_id', $character->unique_id)
            ->select('state')
            ->first();
        
        if (!$characterState) {
            return [
                'is_online' => true,
                'message' => 'Không thể xác định trạng thái nhân vật'
            ];
        }
        
        // state = 0 hoặc state = 1 là nhân vật offline
        if ($characterState->state == 0 || $characterState->state == 1) {
            return [
                'is_online' => false,
                'message' => 'Nhân vật không trực tuyến, có thể thay đổi vũ khí'
            ];
        } else {
            return [
                'is_online' => true,
                'message' => 'Nhân vật đang trực tuyến. Vui lòng thoát game và thử lại sau.'
            ];
        }
    }

    /**
     * Kiểm tra và khóa tài khoản tạm thời để thay đổi vũ khí
     */
    private function lockAccountForWeaponChange($userId)
    {
        try {
            // Bắt đầu transaction
            DB::connection('sqlsrv')->beginTransaction();
            
            // Lấy thông tin member
            $member = DB::connection('sqlsrv')
                ->table('Tbl_ND_Member_Login')
                ->where('member_id', $userId)
                ->select('member_guid', 'member_class')
                ->first();
            
            if (!$member) {
                DB::connection('sqlsrv')->rollBack();
                return [
                    'success' => false,
                    'message' => 'Tài khoản không tồn tại'
                ];
            }
            
            // Nếu tài khoản đang bị khóa tạm thời để thay đổi vũ khí (member_class = 4)
            if ($member->member_class == 4) {
                DB::connection('sqlsrv')->commit();
                return [
                    'success' => true,
                    // Không cần trả về message ở đây nếu thành công
                ];
            }
            
            // Đặt member_class = 4 để đánh dấu tài khoản đang trong quá trình thay đổi vũ khí
            DB::connection('sqlsrv')
                ->table('Tbl_ND_Member_Login')
                ->where('member_guid', $member->member_guid)
                ->update(['member_class' => 4]);
            
            // Tạo bản ghi để theo dõi việc reset
            DB::connection('sqlsrv')->table('weapon_change_schedules')->insert([
                'user_id' => $userId,
                'member_guid' => $member->member_guid,
                'scheduled_at' => now()->addMinutes(15)->format('Y-m-d H:i:s'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            
            DB::connection('sqlsrv')->commit();
            
            return [
                'success' => true,
                // Không cần trả về message ở đây nếu thành công
            ];
            
        } catch (\Exception $e) {
            // Rollback nếu có lỗi
            if (DB::connection('sqlsrv')->transactionLevel() > 0) {
                DB::connection('sqlsrv')->rollBack();
            }
            
            Log::error('Error in lockAccountForWeaponChange: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => $userId
            ]);
            
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra khi khóa tài khoản tạm thời: ' . $e->getMessage()
            ];
        }
    }

    public function getUserCharacters()
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Kiểm tra xem tính năng có được bật không
            $config = WeaponChangeConfig::first();
            if (!$config || !$config->is_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => $config ? $config->maintenance_message : 'Tính năng thay đổi vũ khí đang bảo trì.'
                ], 403);
            }

            $characters = NDV01CharacState::where('hiding', 0)
                ->whereHas('NDV01Charac', function ($query) use ($user) {
                    $query->where('user_id', $user->userid);
                })
                ->with(['NDV01Charac' => function ($query) {
                    $query->select('unique_id', 'chr_name', 'party', 'class', 'state');
                }])
                ->get()
                ->map(function ($char) use ($config) {
                    return [
                        'unique_id' => $char->unique_id,
                        'chr_name' => $char->NDV01Charac->chr_name,
                        'level' => $char->inner_level,
                        'can_change_weapon' => $char->inner_level >= $config->min_level_required,
                        'is_online' => !($char->NDV01Charac->state == 0 || $char->NDV01Charac->state == 1)
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $characters,
                'config' => [
                    'min_level_required' => $config->min_level_required,
                    'change_fee' => $config->change_fee
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getUserCharacters: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách nhân vật'
            ], 500);
        }
    }

    public function getCharacterWeapons($characterId)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
    
            // Kiểm tra nhân vật thuộc về người dùng bằng truy vấn trực tiếp
            $character = DB::connection('cuuamsql2')
                ->table('ND_V01_CharacState')
                ->join('ND_V01_Charac', 'ND_V01_Charac.unique_id', '=', 'ND_V01_CharacState.unique_id')
                ->where('ND_V01_CharacState.unique_id', $characterId)
                ->where('ND_V01_Charac.user_id', $user->userid)
                ->select(
                    'ND_V01_CharacState.unique_id', 
                    'ND_V01_Charac.chr_name', 
                    'ND_V01_CharacState.inner_level', 
                    'ND_V01_Charac.state'
                )
                ->first();
    
            if (!$character) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nhân vật không tồn tại hoặc không thuộc về bạn'
                ], 404);
            }
            
            // Kiểm tra trạng thái online/offline của nhân vật
            $isOnline = !($character->state == 0 || $character->state == 1);
            $stateMessage = $isOnline 
                ? 'Nhân vật đang trực tuyến. Vui lòng thoát game và thử lại sau.' 
                : 'Nhân vật không trực tuyến, có thể thay đổi vũ khí';
            
            // Kiểm tra cấu hình và điều kiện
            $configCheck = $this->checkConfigAndRequirements($character);
            if (!$configCheck['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $configCheck['message']
                ], 403);
            }
            
            // Tìm vũ khí trong tất cả các bảng inventory
            $equippedWeapon = null;
            $tableIndex = 0;
            
            // Kiểm tra từng bảng từ 1-8
            for ($i = 1; $i <= 8; $i++) {
                $inventoryTable = "ND_V02_INVEN_TABLE_" . $i;
                
                $weapon = DB::connection('cuuamsql2')
                    ->select("SELECT * FROM $inventoryTable WHERE cuid = ? AND slot = 25 AND item_type = 19", [$characterId]);
                    
                if (!empty($weapon)) {
                    $equippedWeapon = $weapon[0];
                    $tableIndex = $i;
                    break;
                }
            }
                    
            if (!$equippedWeapon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nhân vật chưa trang bị vũ khí'
                ], 404);
            }
            
            // Xác định loại vũ khí hiện tại từ database
            $weaponType = $this->getWeaponTypeFromDB($equippedWeapon->item_id);
            
            if (!$weaponType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại vũ khí không được hỗ trợ'
                ], 400);
            }
            
            // Lấy danh sách các vũ khí cùng loại từ database
            $availableWeapons = [];
    
            for ($i = $weaponType->min_id; $i <= $weaponType->max_id; $i++) {
                $availableWeapons[] = [
                    'id' => $i,
                    'name' => $this->getWeaponNameFromDB($i),
                    'is_equipped' => ($i === $equippedWeapon->item_id)
                ];
            }
            
            // Định dạng thông tin chi tiết về vũ khí đang trang bị
            $weaponImage = WeaponImage::where('weapon_id', $equippedWeapon->item_id)->first();
            $imageUrl = $weaponImage ? asset('storage/weapon_images/' . $weaponImage->image_url) : '/api/placeholder/80/80';
            
            $detailedWeapon = [
                'id' => $equippedWeapon->item_id,
                'name' => $this->getWeaponNameFromDB($equippedWeapon->item_id),
                'type' => $weaponType->name,
                'item_count' => $equippedWeapon->item_count,
                'durability' => $equippedWeapon->durability,
                'socket_count' => $equippedWeapon->socket_count,
                'socket_item' => $equippedWeapon->socket_item,
                'inchant' => $equippedWeapon->inchant,
                'protect' => $equippedWeapon->protect,
                'add_option' => $equippedWeapon->add_option
            ];
            
            return response()->json([
                'success' => true,
                'data' => [
                    'character' => [
                        'unique_id' => $character->unique_id,
                        'name' => $character->chr_name,
                        'level' => $character->inner_level
                    ],
                    'current_weapon' => $detailedWeapon,
                    'weapons' => $availableWeapons,
                    'change_fee' => $configCheck['change_fee'],
                    'weapon_type' => [
                        'id' => $weaponType->id,
                        'name' => $weaponType->name
                    ]
                ],
                'account_status' => [
                    'is_online' => $isOnline,
                    'message' => $stateMessage
                ]
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error in getCharacterWeapons: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'character_id' => $characterId
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách vũ khí: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function changeWeapon(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Validate request
            $validator = Validator::make($request->all(), [
                'character_id' => 'required|integer',
                'weapon_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 400);
            }

            $characterId = $request->character_id;
            $weaponId = $request->weapon_id;

            // Kiểm tra nhân vật thuộc về người dùng và lấy thông tin state
            $character = DB::connection('cuuamsql2')
                ->table('ND_V01_CharacState')
                ->join('ND_V01_Charac', 'ND_V01_Charac.unique_id', '=', 'ND_V01_CharacState.unique_id')
                ->where('ND_V01_CharacState.unique_id', $characterId)
                ->where('ND_V01_Charac.user_id', $user->userid)
                ->select(
                    'ND_V01_CharacState.unique_id',
                    'ND_V01_Charac.chr_name',
                    'ND_V01_CharacState.inner_level',
                    'ND_V01_Charac.state'
                )
                ->first();

            if (!$character) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nhân vật không tồn tại hoặc không thuộc về bạn'
                ], 404);
            }
            
            // Kiểm tra trạng thái online/offline của nhân vật
            if (!($character->state == 0 || $character->state == 1)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nhân vật đang trực tuyến. Vui lòng thoát game và thử lại sau.'
                ], 403);
            }
            
            // Kiểm tra cấu hình và điều kiện
            $configCheck = $this->checkConfigAndRequirements($character);
            if (!$configCheck['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $configCheck['message']
                ], 403);
            }
            
            // Kiểm tra số dư xu
            $changeFee = $configCheck['change_fee'];
            if ($user->coin < $changeFee) {
                return response()->json([
                    'success' => false,
                    'message' => "Không đủ xu để thay đổi vũ khí. Cần {$changeFee} xu."
                ], 400);
            }

            // Lấy table_index từ bảng inventory where
            $inventoryInfo = NDV02InvenWhereTable::where('cuid', $characterId)->first();
            
            if (!$inventoryInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin inventory của nhân vật'
                ], 404);
            }
            
            $tableIndex = $inventoryInfo->inven_table;
            $inventoryTable = "ND_V02_INVEN_TABLE_" . $tableIndex;
            
            // Lấy vũ khí đang trang bị
            $equippedWeapon = DB::connection('cuuamsql2')
                ->table($inventoryTable)
                ->where('cuid', $characterId)
                ->where('slot', 25)
                ->where('item_type', 19)
                ->first();
                
            if (!$equippedWeapon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nhân vật chưa trang bị vũ khí'
                ], 404);
            }
            
            // Kiểm tra loại vũ khí từ database
            $currentWeaponType = $this->getWeaponTypeFromDB($equippedWeapon->item_id);
            $newWeaponType = $this->getWeaponTypeFromDB($weaponId);
            
            if (!$currentWeaponType || !$newWeaponType) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loại vũ khí không được hỗ trợ'
                ], 400);
            }
            
            if ($currentWeaponType->id !== $newWeaponType->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vũ khí mới phải cùng loại với vũ khí đang trang bị'
                ], 400);
            }
            
            // Khóa tài khoản tạm thời để thay đổi vũ khí
            $lockResult = $this->lockAccountForWeaponChange($user->userid);
            if (!$lockResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $lockResult['message']
                ], 403);
            }
            
            // Nếu khóa thành công, tiếp tục xử lý mà không hiển thị thông báo trung gian
            
            // Bắt đầu transaction để đảm bảo tính nhất quán
            DB::beginTransaction();
            
            try {
                // Trừ xu người dùng
                $user->coin -= $changeFee;
                $user->save();
                
                // Cập nhật vũ khí
                DB::connection('cuuamsql2')
                    ->table($inventoryTable)
                    ->where('cuid', $characterId)
                    ->where('slot', 25)
                    ->where('item_type', 19)
                    ->update(['item_id' => $weaponId]);
                
                // Lưu lịch sử thay đổi
                $this->logWeaponChange(
                    $user->id,
                    $characterId,
                    $character->chr_name,
                    $equippedWeapon->item_id,
                    $weaponId,
                    $changeFee
                );
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Thay đổi vũ khí thành công. Tài khoản sẽ bị khóa tạm thời trong 15 phút.',
                    'data' => [
                        'character_id' => $characterId,
                        'new_weapon_id' => $weaponId,
                        'weapon_name' => $this->getWeaponNameFromDB($weaponId),
                        'remaining_coin' => $user->coin,
                        'account_locked_until' => now()->addMinutes(15)->format('Y-m-d H:i:s'),
                        'unlock_timestamp' => now()->addMinutes(15)->timestamp // Thêm dòng này
                    ]
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error in changeWeapon: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi thay đổi vũ khí: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xác định loại vũ khí dựa vào item_id (phương thức cũ giữ lại để tương thích)
     */
    private function getWeaponType($itemId)
    {
        if ($itemId >= 12086 && $itemId <= 12095) {
            return 'kylan_pve';
        } elseif ($itemId >= 12096 && $itemId <= 12105) {
            return 'kylan_pvp';
        } elseif ($itemId >= 12540 && $itemId <= 12549) {
            return 'quettan_pve';
        } elseif ($itemId >= 12550 && $itemId <= 12559) {
            return 'quettan_pvp';
        }
        
        return null;
    }
    
    
    /**
     * Lấy tên vũ khí dựa vào item_id (phương thức cũ giữ lại để tương thích)
     */
    private function getWeaponName($itemId)
    {
        $weaponNames = [
            // Kỳ Lân PvE
            12086 => 'Kiếm',
            12087 => 'Đao',
            12088 => 'Bổng',
            12089 => 'Quyền',
            12090 => 'Đoản Kiếm',
            12091 => 'Phủ',
            12092 => 'Song Hoàn',
            12093 => 'Thương',
            12094 => 'Côn',
            12095 => 'Cương',
            
            // Kỳ Lân PvP
            12096 => 'Kiếm',
            12097 => 'Đao',
            12098 => 'Bổng',
            12099 => 'Quyền',
            12100 => 'Đoản Kiếm',
            12101 => 'Phủ',
            12102 => 'Song Hoàn',
            12103 => 'Thương',
            12104 => 'Côn',
            12105 => 'Cương',
            
            // Quét Tan PvE
            12540 => 'Kiếm',
            12541 => 'Đao',
            12542 => 'Bổng',
            12543 => 'Quyền',
            12544 => 'Đoản Kiếm',
            12545 => 'Phủ',
            12546 => 'Song Hoàn',
            12547 => 'Thương',
            12548 => 'Côn',
            12549 => 'Cương',
            
            // Quét Tan PvP
            12550 => 'Kiếm',
            12551 => 'Đao',
            12552 => 'Bổng',
            12553 => 'Quyền',
            12554 => 'Đoản Kiếm',
            12555 => 'Phủ',
            12556 => 'Song Hoàn',
            12557 => 'Thương',
            12558 => 'Côn',
            12559 => 'Cương',
        ];
        
        return $weaponNames[$itemId] ?? 'Vũ khí #' . $itemId;
    }
}