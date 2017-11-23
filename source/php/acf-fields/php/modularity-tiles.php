<?php 

if (function_exists('acf_add_local_field_group')) {
    acf_add_local_field_group(array(
    'key' => 'group_59fc24ec4bb83',
    'title' => __('Tiles', 'modularity-testimonials'),
    'fields' => array(
        0 => array(
            'key' => 'field_59fc24f6d3914',
            'label' => __('Tiles', 'modularity-testimonials'),
            'name' => 'modularity-tiles',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 1,
            'max' => 0,
            'layout' => 'block',
            'button_label' => '',
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_59fc255bd3915',
                    'label' => __('Tile type', 'modularity-testimonials'),
                    'name' => 'link_type',
                    'type' => 'radio',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'internal' => __('Internal link', 'modularity-testimonials'),
                        'external' => __('External link', 'modularity-testimonials'),
                        'image' => __('Image (no link)', 'modularity-testimonials'),
                    ),
                    'allow_null' => 0,
                    'other_choice' => 0,
                    'save_other_choice' => 0,
                    'default_value' => '',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),
                1 => array(
                    'key' => 'field_59fc2587d3917',
                    'label' => __('Tile size', 'modularity-testimonials'),
                    'name' => 'tile_size',
                    'type' => 'radio',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'choices' => array(
                        'square' => __('Square (1:1)', 'modularity-testimonials'),
                        'horizontal' => __('Horizontal Rectangle (1:2)', 'modularity-testimonials'),
                        'vertical' => __('Vertical Rectangle (2:1)', 'modularity-testimonials'),
                    ),
                    'allow_null' => 0,
                    'other_choice' => 0,
                    'save_other_choice' => 0,
                    'default_value' => '',
                    'layout' => 'horizontal',
                    'return_format' => 'value',
                ),
                2 => array(
                    'key' => 'field_59fc2599d3918',
                    'label' => __('Page', 'modularity-testimonials'),
                    'name' => 'page',
                    'type' => 'post_object',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_59fc255bd3915',
                                'operator' => '==',
                                'value' => 'internal',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'post_type' => array(
                    ),
                    'taxonomy' => array(
                    ),
                    'allow_null' => 0,
                    'multiple' => 0,
                    'return_format' => 'object',
                    'ui' => 1,
                ),
                3 => array(
                    'key' => 'field_59fc25b2d3919',
                    'label' => __('Link url', 'modularity-testimonials'),
                    'name' => 'link_url',
                    'type' => 'url',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_59fc255bd3915',
                                'operator' => '==',
                                'value' => 'external',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                4 => array(
                    'key' => 'field_59fc25d6d391a',
                    'label' => __('Upload image', 'modularity-testimonials'),
                    'name' => 'custom_image',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_59fc255bd3915',
                                'operator' => '==',
                                'value' => 'image',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'array',
                    'preview_size' => 'thumbnail',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                ),
                5 => array(
                    'key' => 'field_59fc25fbd391b',
                    'label' => __('Title', 'modularity-testimonials'),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => __('Om du inte vill använda den underliggande sidans titel kan du skriva in en annan titel här.', 'modularity-testimonials'),
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_59fc255bd3915',
                                'operator' => '==',
                                'value' => 'internal',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                6 => array(
                    'key' => 'field_59fc261dd391c',
                    'label' => __('Title', 'modularity-testimonials'),
                    'name' => 'title',
                    'type' => 'text',
                    'instructions' => __('Om du inte vill använda den underliggande sidans titel kan du skriva in en annan titel här.', 'modularity-testimonials'),
                    'required' => 1,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_59fc255bd3915',
                                'operator' => '==',
                                'value' => 'external',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                7 => array(
                    'key' => 'field_59fc2635d391d',
                    'label' => __('Lead', 'modularity-testimonials'),
                    'name' => 'lead',
                    'type' => 'textarea',
                    'instructions' => __('Om du inte vill använda den underliggande sidans ingress kan du skriva in en annan ingress här.', 'modularity-testimonials'),
                    'required' => 0,
                    'conditional_logic' => array(
                        0 => array(
                            0 => array(
                                'field' => 'field_59fc255bd3915',
                                'operator' => '==',
                                'value' => 'internal',
                            ),
                            1 => array(
                                'field' => 'field_59fc2587d3917',
                                'operator' => '!=',
                                'value' => 'square',
                            ),
                        ),
                        1 => array(
                            0 => array(
                                'field' => 'field_59fc255bd3915',
                                'operator' => '==',
                                'value' => 'external',
                            ),
                            1 => array(
                                'field' => 'field_59fc2587d3917',
                                'operator' => '!=',
                                'value' => 'square',
                            ),
                        ),
                    ),
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => '',
                    'rows' => '',
                    'new_lines' => '',
                ),
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'mod-tiles',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));
}