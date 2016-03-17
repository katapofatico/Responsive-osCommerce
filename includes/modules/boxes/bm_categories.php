<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

  class bm_categories {
    var $code = 'bm_categories';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function bm_categories() {
      $this->title = MODULE_BOXES_CATEGORIES_TITLE;
      $this->description = MODULE_BOXES_CATEGORIES_DESCRIPTION;

      if ( defined('MODULE_BOXES_CATEGORIES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_CATEGORIES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_CATEGORIES_STATUS == 'True');

        $this->group = ((MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      }
    }

    function execute() {
        global $oscTemplate, $cPath, $PHP_SELF;

        if (tep_not_null(MODULE_BOXES_CATEGORIES_PAGES)) {
            $pages_array = array();

            foreach (explode(';', MODULE_BOXES_CATEGORIES_PAGES) as $page) {
                $page = trim($page);

                if (!empty($page)) {
                    $pages_array[] = $page;
                }
            }

            if (in_array(basename($PHP_SELF), $pages_array)) {
                $OSCOM_CategoryTree = new category_tree();
                $OSCOM_CategoryTree->setCategoryPath($cPath, '<strong>', '</strong>');
                $OSCOM_CategoryTree->setSpacerString('&nbsp;&nbsp;', 1);

                $OSCOM_CategoryTree->setParentGroupString('<ul class="nav nav-pills nav-stacked">', '</ul>', true);

                $category_tree = $OSCOM_CategoryTree->getTree();

                ob_start();
                include(DIR_WS_MODULES . 'boxes/templates/categories.php');
                $data = ob_get_clean();

                $oscTemplate->addBlock($data, $this->group);
            }
        }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_CATEGORIES_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Categories Module', 'MODULE_BOXES_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Pages', 'MODULE_BOXES_CATEGORIES_PAGES', '" . implode(';', $this->get_default_pages()) . "', 'The pages to add the module to.', '6', '0', 'bm_categories_show_pages', 'bm_categories_edit_pages(', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_CATEGORIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_CATEGORIES_STATUS', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'MODULE_BOXES_CATEGORIES_PAGES', 'MODULE_BOXES_CATEGORIES_SORT_ORDER');
    }
    
    function get_default_pages() {
      return array('index.php',
                   'product_info.php');
    }
  }

function bm_categories_show_pages($text) {
    return nl2br(implode("\n", explode(';', $text)));
}

function bm_categories_edit_pages($values, $key) {
    global $PHP_SELF;

    $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
    $files_array = array();
    if ($dir = @dir(DIR_FS_CATALOG)) {
        while ($file = $dir->read()) {
            if (!is_dir(DIR_FS_CATALOG . $file)) {
                if (substr($file, strrpos($file, '.')) == $file_extension) {
                    $files_array[] = $file;
                }
            }
        }
        sort($files_array);
        $dir->close();
    }

    $values_array = explode(';', $values);

    $output = '';
    foreach ($files_array as $file) {
        $output .= tep_draw_checkbox_field('bm_categories_file[]', $file, in_array($file, $values_array)) . '&nbsp;' . tep_output_string($file) . '<br />';
    }

    if (!empty($output)) {
        $output = '<br />' . substr($output, 0, -6);
    }

    $output .= tep_draw_hidden_field('configuration[' . $key . ']', '', 'id="bmrn_files"');

    $output .= '<script>
                function bmrn_update_cfg_value() {
                  var bmrn_selected_files = \'\';

                  if ($(\'input[name="bm_categories_file[]"]\').length > 0) {
                    $(\'input[name="bm_categories_file[]"]:checked\').each(function() {
                      bmrn_selected_files += $(this).attr(\'value\') + \';\';
                    });

                    if (bmrn_selected_files.length > 0) {
                      bmrn_selected_files = bmrn_selected_files.substring(0, bmrn_selected_files.length - 1);
                    }
                  }

                  $(\'#bmrn_files\').val(bmrn_selected_files);
                }

                $(function() {
                  bmrn_update_cfg_value();

                  if ($(\'input[name="bm_categories_file[]"]\').length > 0) {
                    $(\'input[name="bm_categories_file[]"]\').change(function() {
                      bmrn_update_cfg_value();
                    });
                  }
                });
                </script>';

    return $output;
}
