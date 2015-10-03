<?php
namespace Models;

use Resources;

class Panels
{
    public function __construct()
    {
        $this->db = new Resources\Database;
        $this->session = new Resources\Session;
        $this->uri = new Resources\Controller;
    }

    public function isSelected($post, $value)
    {
        if (isset($post)) {
            if ($post === $value) {
                return 'selected';
            }
        }

        return '';
    }

    public function isChecked($post, $value)
    {
        if (isset($post)) {
            if ($post === $value) {
                return 'checked';
            }
        }

        return '';
    }

    public function getPagesList($select, $output = 'html')
    {
        $results = $this->db->getAll('cms_post', array('type' => 'page', 'status' => 'published'), array('id AS value', 'title AS label'));
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "<option value='0'>Page (parent)</option>";
                if ($results) {
                    foreach ($results as $row) {
                        $selected = ($row->value == $select) ? 'selected' : '';
                        $list .= "<option value='$row->value' $selected>$row->label</option>";
                    }
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

    public function getCatList($selected, $output = 'html')
    {
        $results = $this->db->getAll('cms_post_category', array('status' => '1'), array('id', 'category'));
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "<option value='0'>Uncategories</option>";
                if ($results) {
                    foreach ($results as $row) {
                        $selected = ($row->id == $selected) ? 'selected' : '';
                        $list .= "<option value='$row->id' $selected>$row->category</option>";
                    }
                }
                return $list;
                break;
            case 'json':
                return json_encode($result);
                break;
            case 'array':
                return $result;
                break;
        }
    }

    public function getPropList($output = 'html')
    {
        $results = $this->db->getAll('propinsi');
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "";
                if ($results) {
                    foreach ($results as $row) {
                        $list .= "<option value='$row->pr_nama'></option>";
                    }
                }
                return $list;
                break;
            case 'json':
                return json_encode($result);
                break;
            case 'array':
                return $result;
                break;
        }
    }

    public function getKabList($output = 'html')
    {
        $results = $this->db->getAll('kabupaten');
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "";
                if ($results) {
                    foreach ($results as $row) {
                        $list .= "<option value='$row->k_nama'></option>";
                    }
                }
                return $list;
                break;
            case 'json':
                return json_encode($result);
                break;
            case 'array':
                return $result;
                break;
        }
    }

    public function getAccessTypeList($select, $output = 'html')
    {
        $results = array('front', 'back');
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "<option value='0'>Access Type</option>";
                foreach ($results as $row) {
                    $selected = ($row == $select) ? 'selected' : '';
                    $list .= "<option value='$row' $selected>$row</option>";
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

    public function getMenuParentList($select, $output = 'html')
    {
        $results = $this->db->getAll('cms_menu', array('parent_id' => 0, 'published' => 1), array('id', 'title', 'type'));
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "<option value='0'>Menu Parent</option>";
                if ($results) {
                    foreach ($results as $row) {
                        $selected = ($row->id == $select) ? 'selected' : '';
                        $list .= "<option value='$row->id' $selected>$row->title ($row->type)</option>";
                    }
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

    public function getMenuStatusList($select, $output = 'html')
    {
        $results = array(
            (object) array('value' => 1, 'status' => 'Published'),
            (object) array('value' => 0, 'status' => 'Unpublished'),
        );
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "<option value='0'>Menu Status</option>";
                foreach ($results as $row) {
                    $selected = ($row->value == $select) ? 'selected' : '';
                    $list .= "<option value='" . $row->value . "' $selected>" . $row->status . "</option>";
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

    public function getMenuUrlList($select, $output = 'html')
    {
        $results = $this->db->getAll('cms_post', array('status' => 'published'), array('id', 'parent_id', 'title', 'type', 'sef_title'));
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "<option value='0' >Menu Url</option>";
                if ($results) {
                    foreach ($results as $row) {
                        $selected = ($this->postLinkBuilder($row->parent_id, $row->id) == $select) ? 'selected' : '';
                        $separator = ($row->type == 'post') ? '|—' : '';
                        $list .= "<option value='" . $this->postLinkBuilder($row->parent_id, $row->id) . "' $selected>$separator $row->title</option>";
                    }
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

    public function postLinkBuilder($parent_id, $sef_title)
    {
        $this->db
            ->select('id', 'sef_title') // bisa di ganti dengan id (sef_title ganti deangan id)
            ->from('cms_post')
            ->where('id', '=', $sef_title, 'OR')
            ->orderBy('parent_id', 'ASC');

        if ($parent_id > 0) {
            $this->db->where('id', '=', $parent_id);
        }

        if ($data = $this->db->getAll()) {
            foreach ($data as $row) {
                $ids[] = $row->id;
                $sef[] = $row->sef_title;
            }
            return implode('/', array_merge($ids, $sef));
        }
        return false;
    }

    public function lessContent($content = null, $less = null, $tagOpen = null, $tagClose = null)
    {
        $isi_berita = html_entity_decode(strip_tags($content));
        $isi = substr($isi_berita, 0, $less);
        $isi = substr($isi_berita, 0, strrpos($isi, " "));

        return $tagOpen . $isi . $tagClose;
    }

    public function getUserGroupList($select, $output = 'html')
    {
        $results = $this->db->getAll('cms_user_group');
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "";
                if ($results) {
                    foreach ($results as $row) {
                        $selected = ($row->id == $select) ? 'selected' : '';
                        $list .= "<option value='$row->id' $selected>$row->group_name</option>";
                    }
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

    public function getStatusList($select, $output = 'html')
    {
        $results = array('Published', 'Unpublished', 'Archived', 'Trashed');
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "";
                foreach ($results as $row) {
                    $selected = ($row == $select) ? 'selected' : '';
                    $list .= "<option value='$row' $selected>$row</option>";
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

    public function getAccessList($select, $output = 'html')
    {
        $results = array('Public', 'Guest', 'Registered', 'Special');
        switch ($output) {
            default:$list = null;
                break;
            case 'html':
                $list = "";
                foreach ($results as $row) {
                    $selected = ($row == $select) ? 'selected' : '';
                    $list .= "<option value='$row' $selected>$row</option>";
                }
                return $list;
                break;
            case 'json':
                return json_encode($results);
                break;
            case 'array':
                return $results;
                break;
        }
    }

}
