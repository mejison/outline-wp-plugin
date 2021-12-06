<h2>Outline Plugin Settings</h2>
<form method="post" action="options.php">
    <?php settings_fields('plugin-settings-group'); ?>
    <?php do_settings_sections( 'plugin-settings-group' ); ?>

    <table class="form-table">
        <tbody>
            <tr>
                <td>
                    <th>
                        <label for="on_off">Off/On</label>
                    </th>
                </td>
                <td>
                <label class="switch">
                    <input type="checkbox" name="on_off" <?php echo get_option('on_off') == 'on' ? 'checked=checked' : ''; ?> />
                    <span class="slider round"></span>
                </label>
                </td>
            </tr>
            <tr>
            <td>
                <th>
                    <label>Salvation tracked</label>
                </th>
            </td>
            <td>
                <p class="description">
                    Gravity forms hook added on submission for specific form to be selected in settings page (recorded as Salvation eventType)
                </p>
            </td>
            </tr>
            <tr>
                <td>
                    <th>
                        <label>Discipleship tracked</label>
                    </th>
                </td>
                <td>
                    <p class="description">
                        Blog/Page Category/categories to be selected as being discipleship pages to be tracked that way (recorded as discipleship eventType)   
                    </p>
                    <div class="discipleship-section">
                        <div class="pages">
                            <h2>Pages</h2>
                            <ul>
                            <?php foreach(get_pages() as $page) { ?>
                                <li>
                                    <label>
                                        <input type="checkbox" name="discipleship_pages[<?= $page->ID ?>]" <?php echo ! empty(get_option('discipleship_pages')[$page->ID]) ? 'checked=checked' : ''; ?> /><?= $page->post_name; ?>
                                    </label>
                                </li>
                            <?php } ?>
                            </ul>
                        </div>
                        <div class="posts">
                            <h2>Posts</h2>
                            <ul>
                            <?php foreach(get_posts() as $post) { ?>
                                <li>
                                    <label>
                                        <input type="checkbox" name="discipleship_posts[<?= $post->ID ?>]" <?php echo ! empty(get_option('discipleship_posts')[$post->ID]) ? 'checked=checked' : ''; ?> /><?= $post->post_name; ?>
                                    </label>
                                </li>
                            <?php } ?>
                            </ul>
                        </div>
                        <div class="categories">
                            <h2>Categories</h2>
                            <ul>
                            <?php foreach(get_categories() as $category) { ?>
                                <li>
                                    <label>
                                        <input type="checkbox" name="discipleship_categories[<?= $category->cat_ID ?>]" <?php echo ! empty(get_option('discipleship_categories')[$category->cat_ID]) ? 'checked=checked' : ''; ?> /><?= $category->name; ?>
                                    </label>
                                </li>
                            <?php } ?>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>    
<?php submit_button(); ?>
</form>

<style>
    .discipleship-section {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        grid-gap: 30px;
    }
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>