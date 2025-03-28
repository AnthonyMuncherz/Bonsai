<?php
/**
 * Service Card Component
 * 
 * Usage:
 * require_once 'components/service-card.php';
 * echo service_card([
 *   'number' => '01',
 *   'title' => 'Decorating',
 *   'description' => 'Our decorating & painting services...',
 *   'image' => '/Bonsai/Images/Index/IMG_6171.JPG',
 *   'link' => 'single-service.php',
 *   'animation' => 'fade-up', // Optional: AOS animation type
 *   'delay' => 0, // Optional: AOS delay in ms
 * ]);
 */

/**
 * Generate a service card
 * 
 * @param array $args Service card properties
 * @return string HTML for service card
 */
function service_card(array $args) {
    $defaults = [
        'number' => '01',
        'title' => 'Service Title',
        'description' => 'Service description goes here.',
        'image' => '',
        'link' => 'single-service.php',
        'animation' => 'flip-left',
        'delay' => 0,
    ];
    
    $args = array_merge($defaults, $args);
    
    $number = $args['number'];
    $title = $args['title'];
    $description = $args['description'];
    $image = $args['image'];
    $link = $args['link'];
    $animation = $args['animation'];
    $delay = $args['delay'];
    
    // Build animation attributes
    $animation_attr = '';
    if (!empty($animation)) {
        $animation_attr .= ' data-aos="' . $animation . '"';
        if ($delay > 0) {
            $animation_attr .= ' data-aos-delay="' . $delay . '"';
        }
        $animation_attr .= ' data-aos-duration="1000"';
    }
    
    $html = '<div class="service-item bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl"' . $animation_attr . '>';
    $html .= '<div class="relative overflow-hidden">';
    
    if (!empty($image)) {
        $html .= '<img src="' . $image . '" alt="' . $title . '" class="w-full h-64 object-cover transition-transform duration-500 hover:scale-105">';
    }
    
    $html .= '<div class="absolute top-4 left-4 bg-primary text-white text-xl font-bold rounded-full w-10 h-10 flex items-center justify-center">' . $number . '</div>';
    $html .= '</div>';
    $html .= '<div class="p-6">';
    $html .= '<h3 class="text-xl font-bold mb-3 text-dark-olive">' . $title . '</h3>';
    $html .= '<p class="text-olive-dark mb-4">' . $description . '</p>';
    
    if (!empty($link)) {
        $html .= '<a href="' . $link . '" class="text-primary font-semibold hover:underline inline-flex items-center">';
        $html .= 'View Book Details';
        $html .= '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
        $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
        $html .= '</svg>';
        $html .= '</a>';
    }
    
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}
