<?php
/**
 * Project Card Component
 * 
 * Usage:
 * require_once 'components/project-card.php';
 * echo project_card([
 *   'title' => 'Bonsai Haven Project',
 *   'description' => 'Project description text...',
 *   'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
 *   'location' => 'Kuala Lumpur, Malaysia',
 *   'project_type' => 'Bonsai Showcase',
 *   'designer' => 'Shazriq Azrin bin Senawi',
 *   'reverse' => false,
 * ]);
 */

/**
 * Generate a project card
 * 
 * @param array $args Project card properties
 * @return string HTML for project card
 */
function project_card(array $args) {
    $defaults = [
        'title' => 'Project Title',
        'description' => 'Project description goes here.',
        'image' => '',
        'location' => 'Location',
        'project_type' => 'Project Type',
        'designer' => 'Designer Name',
        'reverse' => false,
    ];
    
    $args = array_merge($defaults, $args);
    
    $title = $args['title'];
    $description = $args['description'];
    $image = $args['image'];
    $location = $args['location'];
    $project_type = $args['project_type'];
    $designer = $args['designer'];
    $reverse = $args['reverse'];
    
    $html = '<div class="flex flex-col ' . ($reverse ? 'md:flex-row-reverse' : 'md:flex-row') . ' gap-8 mb-16">';
    
    // Image section
    $html .= '<div class="md:w-1/2">';
    if (!empty($image)) {
        $html .= '<div class="overflow-hidden rounded-lg">';
        $html .= '<img src="' . $image . '" alt="' . $title . '" class="w-full h-auto object-cover transition-transform duration-500 hover:scale-105">';
        $html .= '</div>';
    }
    $html .= '</div>';
    
    // Content section
    $html .= '<div class="md:w-1/2 flex flex-col justify-center">';
    $html .= '<h3 class="text-2xl font-bold mb-4">' . $title . '</h3>';
    $html .= '<p class="text-gray-600 mb-6">' . $description . '</p>';
    
    // Project details
    $html .= '<ul class="space-y-2 mb-6">';
    $html .= '<li class="flex"><span class="font-semibold w-24">Location:</span><span class="text-gray-600">' . $location . '</span></li>';
    $html .= '<li class="flex"><span class="font-semibold w-24">Project:</span><span class="text-gray-600">' . $project_type . '</span></li>';
    $html .= '<li class="flex"><span class="font-semibold w-24">Designer:</span><span class="text-gray-600">' . $designer . '</span></li>';
    $html .= '</ul>';
    
    $html .= '<a href="portfolio.php" class="text-primary font-semibold hover:underline inline-flex items-center">';
    $html .= 'View details';
    $html .= '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
    $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
    $html .= '</svg>';
    $html .= '</a>';
    
    $html .= '</div>';
    
    $html .= '</div>';
    
    return $html;
} 