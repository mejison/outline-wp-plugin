<div id="app">
    <table class="form-table">
        <tbody>
            <tr>
                <td>
                    <th>
                        <label for="on_off">Off/on</label>
                    </th>
                </td>
                <td>
                    <label class="switch">
                        <input type="checkbox" name="on_off" v-model="toggle">
                        <span class="slider round"></span>
                    </label>
                    <!-- <p class="description">
                        On and Off toogle for collection of data
                    </p> -->
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
                                <li v-for="(page, index) in pages" :key="index">
                                    <label>
                                        <input type="checkbox" :name="page.ID" v-model="discipleshipPages[page.ID]" />{{ page.post_name }}
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="categories">
                            <h2>Categories</h2>
                            <ul>
                                <li v-for="(category, index) in categories" :key="index">
                                    <label>
                                        <input type="checkbox" :name="category.cat_id" v-model="discipleshipCategories[category.ID]" />{{ category.name }}
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

   <h1>test=  <?php echo get_option('new_option_name'); ?> </h1>


   <form method="post" action="options.php">
       <?php settings_fields('plugin-settings-group'); ?>
        <?php do_settings_sections( 'plugin-settings-group' ); ?>

         <input type="text" name="new_option_name" value="<?php echo get_option('new_option_name'); ?>" />
        <?php submit_button(); ?>
   </form>

    <button class="button button-primary" type="button" @click.prevent.stop="onUpdate">Save Changes</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.0/vue.min.js"></script>
<script>
    new Vue({
        el: '#app',

        data: function() {
            return {
                toggle: false,
                pages: <?php echo json_encode(get_pages()); ?>,
                categories: <?php echo json_encode(get_categories()); ?>,
                discipleshipPages: {},
                discipleshipCategories: {},
            }
        },

        methods: {
            onUpdate() {
                let data = {
                    test: '2'
                };

                let body = [];
                for (let property in data) {
                    let encodedKey = encodeURIComponent(property);
                    let encodedValue = encodeURIComponent(data[property]);
                    body.push(encodedKey + "=" + encodedValue);
                }

                body = body.join("&");

                fetch('options.php', { 
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                    },
                    body: body,
                })
            }
        }
    })
</script>

<style>
.discipleship-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
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