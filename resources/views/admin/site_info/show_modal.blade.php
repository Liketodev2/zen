<div class="modal fade" id="siteInfoModal" tabindex="-1" aria-labelledby="siteInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="siteInfoModalLabel">Site Information</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item">
              <strong>Phone:</strong>
              @if($info->phone)
                {{ $info->phone }}
              @else
                <span class="text-danger">No Phone info</span>
              @endif
            </li>
  
            <li class="list-group-item">
              <strong>Email:</strong>
              @if($info->email)
                {{ $info->email }}
              @else
                <span class="text-danger">No Email info</span>
              @endif
            </li>
  
            <li class="list-group-item">
              <strong>ABN:</strong>
              @if($info->abn)
                {{ $info->abn }}
              @else
                <span class="text-danger">No ABN info</span>
              @endif
            </li>
  
            <li class="list-group-item">
              <strong>Registered Tax Agent:</strong>
              @if($info->tax_agent)
                {{ $info->tax_agent }}
              @else
                <span class="text-danger">No Registered Tax Agent info</span>
              @endif
            </li>
  
            <li class="list-group-item">
              <strong>Facebook:</strong>
              @if($info->facebook)
                <a href="{{ $info->facebook }}" target="_blank">{{ $info->facebook }}</a>
              @else
                <span class="text-danger">No Facebook link</span>
              @endif
            </li>
  
            <li class="list-group-item">
              <strong>Instagram:</strong>
              @if($info->instagram)
                <a href="{{ $info->instagram }}" target="_blank">{{ $info->instagram }}</a>
              @else
                <span class="text-danger">No Instagram link</span>
              @endif
            </li>
  
            <li class="list-group-item">
              <strong>X (Twitter):</strong>
              @if($info->x)
                <a href="{{ $info->x }}" target="_blank">{{ $info->x }}</a>
              @else
                <span class="text-danger">No X (Twitter) link</span>
              @endif
            </li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  