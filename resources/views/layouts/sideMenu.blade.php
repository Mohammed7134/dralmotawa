<nav id="sidebar" class="inactive">
    <div class="sidebar-header">
        <h3>التصنيفـات</h3>
    </div>
    <ul class="list-unstyled components">

        @foreach($categories as $category)
        <li class="d-flex justify-content-between">
            <a href="/category/{{$category->category_url}}" class="category-link">{{$category->category_name}}</a>
            <p>{{$category->wisdoms()->count()}}</p>
        </li>
        @endforeach
    </ul>
</nav>
<button id="xmark" type="button" aria-label="Close" class="btn-close"></button>