<?php

add_action('admin_menu', 'lesson_pannel');

function lesson_pannel() {
    add_menu_page('Leçon', 'Leçon', 'activate_plugins', 'lesson-pannel', 'render_pannel', null, 85);
};

