{{ null,
    $employees = array_values($posts->filter(function ($post) {
            $post->spreadMeta();
            return $post->short_view;
        })->all())
}}

{{ null, $leaders = array_values($posts->diff($employees)->all()) }}

@include($viewFolder . '.leaders')
@include($viewFolder . '.employees')