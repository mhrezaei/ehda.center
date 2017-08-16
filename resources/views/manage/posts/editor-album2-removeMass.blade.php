{{--
|--------------------------------------------------------------------------
| This is called when an attached file is removed, whether it is saved in the post or not.
| The file and corresponding record will be deleted and a blank area is repaced at the end.
|--------------------------------------------------------------------------
| $option is passed via the automatic ManageControllerTrait method, and contains the hashid of newly inserted file.
--}}

{{ '' , isset($option)? Upload::removeFile($option) : '' }}
<script>postFileCounterUpdate('-')</script>