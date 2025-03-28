<?php
/**
 * Breadcrumbs component
 * 
 * Usage:
 * require_once 'components/breadcrumbs.php';
 * echo breadcrumbs([
 *   'Home' => 'index.php',
 *   'About' => 'about.php',
 *   'Team' => ''
 * ]);
 */

/**
 * Generate breadcrumbs HTML
 * 
 * @param array $items Associative array of breadcrumb items (label => url)
 * @param string $background Optional background image URL
 * @param string $title Optional title to display above breadcrumbs
 * @return string HTML for breadcrumbs
 */
function breadcrumbs(array $items, string $background = '', string $title = '') {
    $html = '<section class="bg-secondary py-8 md:py-12">';
    
    if (!empty($background)) {
        $html = '<section class="relative py-16 md:py-24" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(\'' . $background . '\') no-repeat center/cover;">';
    }
    
    $html .= '<div class="container mx-auto text-center">';
    
    if (!empty($title)) {
        $html .= '<h1 class="text-4xl md:text-5xl font-bold mb-6' . (!empty($background) ? ' text-white' : '') . '">' . $title . '</h1>';
    }
    
    $html .= '<div class="flex justify-center items-center flex-wrap">';
    
    $i = 0;
    $count = count($items);
    
    foreach ($items as $label => $url) {
        $i++;
        $isLast = ($i === $count);
        
        $html .= '<span class="' . (!empty($background) ? 'text-white' : '') . '">';
        
        if (!empty($url) && !$isLast) {
            $html .= '<a href="' . $url . '" class="hover:text-primary transition-colors">' . $label . '</a>';
        } else {
            $html .= '<span class="' . (!$isLast ? 'text-gray-500' : 'font-semibold') . '">' . $label . '</span>';
        }
        
        $html .= '</span>';
        
        if (!$isLast) {
            $html .= '<span class="mx-2' . (!empty($background) ? ' text-white' : '') . '">/</span>';
        }
    }
    
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</section>';
    
    return $html;
} 