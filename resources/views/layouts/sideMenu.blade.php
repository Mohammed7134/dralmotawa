<nav id="sidebar" class="inactive">
    <div class="sidebar-header">
        <h3>التصنيفـات</h3>
    </div>
    <ul class="list-unstyled components">
        @foreach($categories as $id => $name)
        <li><a href="/category/{{$id}}" class="category-link">{{$name}}</a></li>
        @endforeach
    </ul>
</nav>
<button id="xmark" type="button" aria-label="Close" class="btn-close"></button>