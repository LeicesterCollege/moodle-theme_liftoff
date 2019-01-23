<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Core Renderer for the liftoff theme.
 * Renderers to align Moodle's HTML with that expected by Bootstrap and the Boost parent theme.
 *
 * @package   theme_liftoff
 * @copyright 2018 Leicester College (Developer: Alan Ball)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_liftoff\output;

use coding_exception;
use html_writer;
use tabobject;
use tabtree;
use custom_menu_item;
use custom_menu;
use block_contents;
use navigation_node;
use action_link;
use stdClass;
use moodle_url;
use preferences_groups;
use action_menu;
use help_icon;
use single_button;
use paging_bar;
use context_course;
use pix_icon;

defined('MOODLE_INTERNAL') || die;

class core_renderer extends \theme_boost\output\core_renderer {

    public function render_my_courses() {
        global $CFG, $USER, $COURSE;

        $menu = new custom_menu();

        if (isloggedin() && !isguestuser() ) {
            $branchtitle = "My Courses";
            $branchlabel = $branchtitle;
            $branchurl   = new moodle_url('/my/index.php');
            $branchsort  = 9000;
 
            $branch = $menu->add($branchlabel, $branchurl, $branchtitle, $branchsort);
            if ($courses = enrol_get_my_courses(NULL, 'fullname ASC')) {
                $branch->add('View all of my courses...', new moodle_url('/my/index.php'), format_string('Dashboard'));
                foreach ($courses as $course) {
                    if ($course->visible){
                        $branch->add(format_string($course->fullname), new moodle_url('/course/view.php?id='.$course->id), format_string($course->shortname));
                    }
                }
            } else {
                $noenrolments = 'No Enrolments Found';
                $branch->add($noenrolments, new moodle_url('/'), $noenrolments);
            }
            
        }
        $content = '';
        foreach ($menu->get_children() as $item) {
            $context = $item->export_for_template($this);
            $content .= $this->render_from_template('theme_liftoff/course_menu_items', $context);
        }

        return $content;
    }

    /**
     * Wrapper for dhashboard header elements.
     *
     * @return string HTML to display the main header.
     */
    public function dashboard_header() {
        global $PAGE;

        $header = new stdClass();
        $header->pageheadingbutton = $this->page_heading_button();
        return $this->render_from_template('theme_liftoff/dashboard_header', $header);
    }

}