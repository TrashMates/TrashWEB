<div class="pagination">
    <ul>
        @for($i = 1; $i <= ceil($count/50); $i++)
            @if (($i > $page - 3) && ($i < $page + 3))
                @if($i == $page)
                    <a href="{{ "?page=" . $i }}">
                        <li class="active">{{ $i }}</li>
                    </a>
                @else
                    <a href="{{ "?page=" . $i }}">
                        <li>{{ $i }}</li>
                    </a>
                @endif
            @endif
        @endfor
    </ul>
</div>