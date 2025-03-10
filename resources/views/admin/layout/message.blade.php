@foreach(['success','danger'] as $item)
    @if(session($item))
        <div class="flash-message">
            <div class="alert alert-{{ $item }}" role="alert">
                <strong class="font-weight-100 font-size-14">{{ session($item) }}  </strong>
            </div>
        </div>

    @endif
@endforeach