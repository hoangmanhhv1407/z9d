<div class="services-sidebar" style="margin-top: 20px">
    <div class="sidebar-widget contact-widget">
        <div class="sidebar-title"><h3>Liên hệ</h3></div>
        <ul class="info-list">
            @if(!empty($settingOptions['address']))
            <li>{!! $settingOptions['address'] !!}</li>
            @endif
            @if(!empty($settingOptions['telephone']))
                <li>Phone : <a href="tel:{!! $settingOptions['telephone'] !!}">{!! $settingOptions['telephone'] !!}</a></li>
            @endif
            @if(!empty($settingOptions['email']))
                <li>Email : <a href="mailto:{!! $settingOptions['email'] !!}">{!! $settingOptions['email'] !!}</a></li>
            @endif
            @if(!empty($settingOptions['link_facebook']))
                <li>Facebook : <a href="{!! $settingOptions['link_facebook'] !!}" target="_blank">{!! $settingOptions['link_facebook'] !!}</a></li>
            @endif
            @if(!empty($settingOptions['zalo']))
                <li>Zalo : <a href="{!! $settingOptions['zalo'] !!}" target="_blank">{!! $settingOptions['zalo'] !!}</a></li>
            @endif
            @if(!empty($settingOptions['youtube']))
                <li>Youtube : <a href="{!! $settingOptions['youtube'] !!}" target="_blank">{!! $settingOptions['youtube'] !!}</a></li>
            @endif
        </ul>
    </div>
</div>