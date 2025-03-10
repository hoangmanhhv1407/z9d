import React, { useState } from 'react';
import { AlertCircle, X, Check, ChevronRight, Users, Heart } from 'lucide-react';

const TermsModal = ({ title, content, onClose }) => {
  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div className="bg-white rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] flex flex-col relative">
        <button 
          onClick={onClose}
          className="absolute top-4 right-4 text-gray-400 hover:text-gray-600"
        >
          <X size={24} />
        </button>
        <h3 className="text-xl font-bold mb-4">{title}</h3>
        <div className="overflow-y-auto prose prose-sm">
          {content}
        </div>
      </div>
    </div>
  );
};

const TermsPopup = ({ onAccept, onClose }) => {
  const [accepted, setAccepted] = useState(false);
  const [selectedTerm, setSelectedTerm] = useState(null);

  const terms = {
    about: {
        title: "Về ZPLAY9D ( Team Z )",
        content: (
          <div className="space-y-4">
            <div className="flex items-center space-x-2 mb-4">
              <Users className="w-6 h-6 text-blue-500" />
              <h3 className="font-bold text-gray-800">Đội ngũ phát triển</h3>
            </div>
            <p>
              ZPLAY9D ( Team Z ) được thành lập bởi Team Z từ tháng 12/2020 ra mắt phiên bản chính thức , gắn bó với cộng đồng Cửu Long Tranh Bá đã nhiều năm. 
              Chúng tôi hy vọng sẽ mang tới cho ae cộng đồng 1 sân chơi đẹp và đầy tính giải trí .
            </p>
  
            <div className="flex items-center space-x-2 mb-4">
              <Heart className="w-6 h-6 text-red-500" />
              <h3 className="font-bold text-gray-800">Tâm huyết và Sứ mệnh</h3>
            </div>
            
            <div className="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg">
              <p className="text-gray-700 mb-3">
                <span className="font-semibold">Tại sao chúng tôi tạo ra máy chủ này?</span>
              </p>
              <ul className="list-disc list-inside space-y-2 text-gray-600">
                <li>Khôi phục lại phiên bản hoàng kim của Cửu Long Tranh Bá mà nhiều game thủ yêu thích</li>
                <li>Tạo môi trường chơi game lành mạnh, không pay-to-win, không hack/cheat</li>
                <li>Xây dựng cộng đồng game thủ đoàn kết, văn minh và tích cực</li>
                <li>Bảo tồn những giá trị văn hóa và kỷ niệm đẹp về tựa game huyền thoại này</li>
              </ul>
            </div>
  
            <div className="bg-yellow-50 p-4 rounded-lg">
              <p className="text-gray-700">
                <span className="font-semibold">Cam kết của chúng tôi:</span>
              </p>
              <ul className="list-disc list-inside space-y-2 text-gray-600">
                <li>Quản lý minh bạch, công bằng với mọi game thủ</li>
                <li>Liên tục cập nhật, sửa lỗi và cải thiện trải nghiệm game</li>
                <li>Lắng nghe ý kiến đóng góp từ cộng đồng</li>
                <li>Tổ chức các sự kiện thường xuyên để kết nối anh em game thủ</li>
                <li>Bảo vệ quyền lợi chính đáng của người chơi</li>
              </ul>
            </div>
          </div>
        )
      },
    account: {
      title: "Thỏa thuận Tài khoản",
      content: (
        <div>
          <p>- Mọi thông tin liên quan đến tài khoản của anh em đều thuộc quyền quản lý của ZPLAY9D ( Team Z )-VN</p>
          <p>- Anh em có trách nhiệm bảo mật thông tin tài khoản và chịu trách nhiệm về mọi hoạt động</p>
          <p>- ZPLAY9D ( Team Z ) không chịu trách nhiệm về những thiệt hại phát sinh do anh em không tuân thủ quy định bảo mật</p>
          <p>- Việc chia sẻ thông tin tài khoản là hoàn toàn rủi ro của cá nhân người chơi</p>
          <p>- Nghiêm cấm sử dụng VPN hoặc các phần mềm giả lập IP</p>
          <p>- Khi đăng ký, anh em đồng ý nhận thông báo từ ZPLAY9D ( Team Z ) qua các kênh liên lạc</p>
          <p>- Tên nhân vật và bang hội không được giả mạo hoặc xúc phạm BQT</p>
          <p>- Người chơi dưới 18 tuổi cần có sự đồng ý của phụ huynh</p>
        </div>
      )
    },
    donations: {
      title: "Quy định về Đóng góp",
      content: (
        <div>
          <p>- Mọi đóng góp cho ZPLAY9D ( Team Z ) đều là tự nguyện và không được hoàn lại</p>
          <p>- Thay vì bán xu, chúng tôi nhận đóng góp và tặng xu như một phần thưởng</p>
          <p>- BQT không chịu trách nhiệm về mất mát vật phẩm, bao gồm cả những vật phẩm từ đóng góp</p>
          <p>- Người đóng góp và không đóng góp đều được đối xử công bằng</p>
          <p>- Tài khoản bị khóa sẽ không được hoàn trả vật phẩm</p>
          <p>- Hành vi dispute hay chargeback sẽ bị khóa tài khoản vĩnh viễn</p>
        </div>
      )
    },
    cheating: {
      title: "Quy định về Hack và Gian lận",
      content: (
        <div>
          <p>- Sử dụng hack, cheat sẽ bị khóa vĩnh viễn IP, máy và tài khoản</p>
          <p>- Lợi dụng lỗi server hoặc gian lận sẽ bị khóa vĩnh viễn</p>
          <p>- Lừa đảo người chơi dưới mọi hình thức sẽ bị khóa vĩnh viễn</p>
          <p>- Yêu cầu chơi game trung thực, công bằng</p>
          <p>- Báo cáo ngay cho BQT nếu phát hiện lỗi game</p>
        </div>
      )
    },
    respect: {
      title: "Tôn trọng BQT và Máy chủ",
      content: (
        <div>
          <p>- Không được công kích hay thiếu tôn trọng BQT</p>
          <p>- Không được công khai thách thức quyết định của BQT</p>
          <p>- Quyết định của BQT là cuối cùng</p>
          <p>- BQT có quyền khóa tài khoản để điều tra</p>
          <p>- Mọi khiếu nại cần gửi riêng cho BQT</p>
          <p>- BQT làm việc tình nguyện vì cộng đồng</p>
        </div>
      )
    }
  };

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn">
      <div className="bg-gradient-to-br from-white via-blue-50 to-indigo-50 rounded-xl p-8 max-w-2xl w-full mx-4 shadow-2xl relative max-h-[90vh] flex flex-col">
        <button 
          onClick={onClose}
          className="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors"
        >
          <X size={24} />
        </button>

        <div className="flex items-center mb-6 flex-shrink-0">
          <AlertCircle className="w-8 h-8 text-yellow-500 mr-3" />
          <h2 className="text-2xl font-bold text-gray-800">Chào mừng anh em đến với Máy chủ ZPLAY9D ( Team Z ) TEAM!</h2>
        </div>

        <div className="prose prose-sm max-w-none space-y-4 overflow-y-auto flex-grow pr-2">
          <div className="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <p className="text-yellow-700">
              Lưu ý: Đây là máy chủ phi lợi nhuận do anh em trong cộng đồng tự quản lý. Chúng tôi hoàn toàn không có mối liên hệ nào với các đơn vị phát hành chính thức như JoongWong Games, RedFox Games, hay DZO.
            </p>
          </div>

          <div className="bg-gradient-to-r from-blue-100 to-indigo-100 p-4 rounded-lg border border-blue-200">
            <p className="text-gray-700 leading-relaxed">
              ZPLAY9D ( Team Z ) được thành lập bởi Team Z từ tháng 12/2020 ra mắt phiên bản chính thức , gắn bó với cộng đồng Cửu Long Tranh Bá đã nhiều năm. 
              Với tình yêu với Cửu Long Tranh Bá và khát khao tạo ra một môi trường chơi game lành mạnh. 
              Chúng tôi tin rằng một tựa game huyền thoại như Cửu Long xứng đáng có một cộng đồng game thủ văn minh, 
              nơi anh em có thể cùng nhau tận hưởng những giây phút đáng nhớ mà không phải lo lắng về pay-to-win hay hack/cheat.
            </p>
          </div>
          <div className="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
            <p className="text-blue-700">
              Để duy trì máy chủ, chúng tôi rất mong nhận được sự ủng hộ từ cộng đồng game thủ. Mọi đóng góp đều là tự nguyện và không hoàn lại. Khi tham gia máy chủ, anh em vui lòng tuân thủ các điều khoản sử dụng và quy định của cộng đồng.
            </p>
          </div>

          <div className="bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <p className="text-red-700">
              Nếu anh em muốn trải nghiệm phiên bản chính thức của Cửu Long Tranh Bá, xin hãy quay lại trang web của nhà phát hành. Chúng tôi khuyến khích anh em ủng hộ phiên bản game gốc.
            </p>
          </div>

 
          <div className="bg-gray-50 p-4 rounded border border-gray-200">
            <p className="font-semibold text-gray-700 mb-2">
              Trước khi bắt đầu hành trình, anh em cần đọc kỹ và đồng ý với:
            </p>
            <ul className="space-y-2">
              {Object.entries(terms).map(([key, term]) => (
                <li key={key} 
                    className="flex items-center justify-between p-2 hover:bg-gray-100 rounded cursor-pointer"
                    onClick={() => setSelectedTerm(key)}>
                  <span className="text-gray-600">{term.title}</span>
                  <ChevronRight className="w-5 h-5 text-gray-400" />
                </li>
              ))}
            </ul>
          </div>
        </div>

        <div className="flex items-start mb-6 mt-6 flex-shrink-0">
          <div className="flex items-center h-5">
            <input
              id="terms"
              type="checkbox"
              checked={accepted}
              onChange={(e) => setAccepted(e.target.checked)}
              className="w-4 h-4 border-gray-300 rounded text-blue-600 focus:ring-blue-500"
            />
          </div>
          <label htmlFor="terms" className="ml-2 text-sm text-gray-600">
            Tôi đã đọc kỹ và hoàn toàn đồng ý với các điều khoản, quy định của máy chủ. Tôi cam kết sẽ là một thành viên tích cực, văn minh của cộng đồng.
          </label>
        </div>

        <div className="flex justify-end space-x-4 flex-shrink-0">
          <button
            onClick={onClose}
            className="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors"
          >
            Quay lại
          </button>
          <button
            onClick={onAccept}
            disabled={!accepted}
            className={`flex items-center px-6 py-2 rounded-lg text-white transition-colors
              ${accepted 
                ? 'bg-blue-600 hover:bg-blue-700' 
                : 'bg-gray-400 cursor-not-allowed'}`}
          >
            <Check className="w-5 h-5 mr-2" />
            Tham gia ngay
          </button>
        </div>

        {selectedTerm && (
          <TermsModal
            title={terms[selectedTerm].title}
            content={terms[selectedTerm].content}
            onClose={() => setSelectedTerm(null)}
          />
        )}
      </div>
    </div>
  );
};

export default TermsPopup;