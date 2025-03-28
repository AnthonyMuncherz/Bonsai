<?php
/**
 * Gallery Component
 * 
 * Usage:
 * require_once 'components/gallery.php';
 * $images = [
 *   [
 *     'src' => '/Bonsai/Images/Index/IMG_6171.JPG',
 *     'alt' => 'Bonsai Tree',
 *     'title' => 'Elegant Bonsai'
 *   ],
 *   // More images...
 * ];
 * echo gallery($images, 'grid');
 */

/**
 * Generate a gallery
 * 
 * @param array $images Array of image data (src, alt, title)
 * @param string $type Type of gallery: 'grid' or 'masonry'
 * @param int $columns Number of columns (default 3)
 * @return string HTML for gallery
 */
function gallery(array $images, string $type = 'grid', int $columns = 3) {
    if (empty($images)) {
        return '';
    }
    
    $columnClass = '';
    switch ($columns) {
        case 2:
            $columnClass = 'grid-cols-1 sm:grid-cols-2';
            break;
        case 3:
            $columnClass = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
            break;
        case 4:
            $columnClass = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4';
            break;
        default:
            $columnClass = 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
    }
    
    $html = '<div class="' . ($type === 'masonry' ? 'columns-1 sm:columns-2 lg:columns-' . $columns . ' gap-4' : 'grid ' . $columnClass . ' gap-4') . '">';
    
    foreach ($images as $image) {
        $src = $image['src'] ?? '';
        $alt = $image['alt'] ?? 'Gallery Image';
        $title = $image['title'] ?? '';
        
        if (empty($src)) {
            continue;
        }
        
        $html .= '<div class="gallery-item ' . ($type === 'masonry' ? 'mb-4' : '') . ' overflow-hidden rounded-lg cursor-pointer" data-src="' . $src . '">';
        $html .= '<img src="' . $src . '" alt="' . $alt . '" class="w-full h-auto transition-transform duration-500 hover:scale-105">';
        
        if (!empty($title)) {
            $html .= '<div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-3 opacity-0 hover:opacity-100 transition-opacity duration-300">';
            $html .= '<h4 class="text-lg font-bold">' . $title . '</h4>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
    }
    
    $html .= '</div>';
    
    return $html;
} 