<div class="col-md-4">
    <div class="shadow-lg p-3 my-3 rounded blog_post_container">
        @include("shared.blogPost", ["displayed"=>$wisdom->text, "wisdom"=>$wisdom, "title"=>""])
    </div>
</div>
@include("shared.fullCard")