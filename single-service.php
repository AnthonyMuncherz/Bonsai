<?php
// Get service from query parameter
$service = isset($_GET['service']) ? $_GET['service'] : 'design';

// Service data
$services = [
    'design' => [
        'title' => 'Bonsai Design & Styling',
        'image' => '/Bonsai/Images/Index/IMG_6171.JPG',
        'short_description' => 'Our expert bonsai artists can help design and style your tree to create the perfect living art piece for your space.',
        'full_description' => 'Our expert bonsai artists can transform ordinary plants into extraordinary works of art through skilled design and styling techniques. We work closely with you to understand your aesthetic preferences and the environment where your bonsai will be displayed. Using traditional Japanese techniques combined with modern approaches, we carefully prune, wire, and shape your tree to bring out its unique character and beauty. Whether you have a specific vision in mind or want to explore different styling options, our team will guide you through the process to create a bonsai that brings joy and tranquility to your space.',
        'features' => [
            'Professional styling by certified bonsai artists',
            'Customized design consultation',
            'Traditional and contemporary styling techniques',
            'Follow-up care instructions',
            'Styling documentation with before and after photos'
        ],
        'pricing' => 'Starting from RM350 per tree, depending on size and complexity'
    ],
    'maintenance' => [
        'title' => 'Bonsai Maintenance',
        'image' => '/Bonsai/Images/Index/IMG_6168.JPG',
        'short_description' => 'Regular maintenance services to keep your bonsai healthy, including pruning, wiring, repotting, and seasonal care.',
        'full_description' => 'Keep your bonsai thriving with our comprehensive maintenance service. Proper bonsai care requires regular attention and expertise, especially in Malaysia\'s tropical climate. Our maintenance service includes seasonal pruning, wiring adjustments, soil management, fertilization, and pest control. We offer flexible maintenance schedules tailored to your bonsai\'s specific needs, whether it\'s a once-off service or regular scheduled maintenance. Our expert technicians are trained to identify early signs of health issues and provide preventative care to ensure your bonsai remains beautiful and healthy for years to come.',
        'features' => [
            'Routine pruning and trimming',
            'Wire application and adjustment',
            'Seasonal repotting when necessary',
            'Fertilization and pest management',
            'Health assessment and recommendations'
        ],
        'pricing' => 'Monthly plans from RM150, or one-time service from RM250'
    ],
    'workshops' => [
        'title' => 'Bonsai Workshops',
        'image' => '/Bonsai/Images/Index/IMG_6179.JPG',
        'short_description' => 'Learn the art of bonsai through our hands-on workshops for beginners and advanced enthusiasts.',
        'full_description' => 'Dive into the fascinating world of bonsai through our interactive workshops designed for all skill levels. Our workshops provide hands-on experience under the guidance of experienced bonsai masters. From beginner basics to advanced techniques, we offer a range of educational experiences that will deepen your understanding and appreciation of this ancient art form. Each participant works with their own tree, learning essential skills like pruning, wiring, potting, and design principles. Small class sizes ensure personalized attention, and all materials are provided, including a bonsai tree, pot, soil, and tools for use during the workshop.',
        'features' => [
            'Classes for all skill levels (beginner, intermediate, advanced)',
            'Hands-on training with experienced bonsai masters',
            'All materials provided including tree and pot',
            'Take-home care guide',
            'Follow-up support via email'
        ],
        'pricing' => 'Workshops from RM280 per person, including all materials'
    ],
    'custom' => [
        'title' => 'Custom Bonsai Creation',
        'image' => '/Bonsai/Images/Index/IMG_6177.JPG',
        'short_description' => 'Commission a custom bonsai design for your home, office, or as a special gift for someone.',
        'full_description' => 'Create something truly unique with our custom bonsai creation service. Whether for your home, office, or as a meaningful gift, a commissioned bonsai is a living piece of art that carries special significance. Our process begins with a detailed consultation to understand your vision, preferences, and the environment where the bonsai will live. We then select the perfect species and style that matches your requirements, considering factors like light conditions, available space, and maintenance capabilities. Our artists will work closely with you throughout the creation process, providing updates and seeking input at key stages to ensure the final piece perfectly aligns with your expectations.',
        'features' => [
            'Personalized consultation and design planning',
            'Selection of appropriate species for your environment',
            'Custom pot selection or commissioning',
            'Progress updates throughout the creation process',
            'Delivery and installation at your location'
        ],
        'pricing' => 'Custom creations from RM500, depending on species, size, and complexity'
    ],
    'health' => [
        'title' => 'Bonsai Health Consultation',
        'image' => '/Bonsai/Images/Index/IMG_6174.JPG',
        'short_description' => 'Expert advice and solutions for bonsai health issues, pest control, and disease management.',
        'full_description' => 'Is your bonsai showing signs of distress? Our health consultation service provides expert diagnosis and treatment plans for ailing bonsai trees. Common issues in Malaysia\'s climate include fungal infections, pest infestations, and stress from improper watering or light exposure. Our specialists will conduct a thorough examination of your tree, identify the underlying causes of health problems, and develop a customized treatment plan. We provide both on-site consultations and emergency care for critical situations. In addition to addressing current issues, we will educate you on preventative measures to keep your bonsai healthy in the future.',
        'features' => [
            'Comprehensive health assessment',
            'Diagnosis of diseases and pest problems',
            'Customized treatment plans',
            'Emergency intervention for critical issues',
            'Preventative care education'
        ],
        'pricing' => 'Consultations from RM150, with treatment costs determined after diagnosis'
    ],
    'garden' => [
        'title' => 'Bonsai Garden Design',
        'image' => '/Bonsai/Images/Index/IMG_6169.JPG',
        'short_description' => 'Create a harmonious bonsai garden display with our professional design and installation services.',
        'full_description' => 'Transform your space with a professionally designed bonsai garden that creates a peaceful retreat for contemplation and enjoyment. Our garden design service integrates bonsai displays with complementary elements like viewing stones, accent plants, and appropriate display stands to create a harmonious composition. We consider spatial flow, visual balance, seasonal changes, and the unique characteristics of your environment to create a garden that evolves beautifully throughout the year. From compact indoor displays to extensive outdoor gardens, we can create a bonsai landscape that reflects your personal aesthetic while honoring traditional design principles. Our service includes ongoing maintenance recommendations to keep your garden thriving.',
        'features' => [
            'Site analysis and environmental assessment',
            'Custom design based on your space and preferences',
            'Selection and arrangement of complementary elements',
            'Professional installation',
            'Seasonal maintenance planning'
        ],
        'pricing' => 'Design consultations from RM300, with full installation priced based on scope and materials'
    ]
];

// Set default if service not found
if (!isset($services[$service])) {
    $service = 'design';
}

// Get current service data
$current_service = $services[$service];

// Set page title
$page_title = $current_service['title'];

require_once 'includes/header.php';
require_once 'components/breadcrumbs.php';

echo breadcrumbs([
    'Home' => 'index.php',
    'Services' => 'services.php',
    $current_service['title'] => ''
]);
?>

<!-- Service Hero -->
<section class="py-16">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row gap-12">
            <!-- Image -->
            <div class="md:w-1/2">
                <div class="rounded-lg overflow-hidden shadow-xl">
                    <img src="<?php echo $current_service['image']; ?>" alt="<?php echo $current_service['title']; ?>" class="w-full h-auto">
                </div>
            </div>
            
            <!-- Content -->
            <div class="md:w-1/2">
                <h1 class="text-4xl font-bold mb-6"><?php echo $current_service['title']; ?></h1>
                <p class="text-xl mb-6"><?php echo $current_service['short_description']; ?></p>
                <div class="mb-8">
                    <p class="font-bold text-lg mb-2">Pricing:</p>
                    <p class="text-primary text-lg"><?php echo $current_service['pricing']; ?></p>
                </div>
                <a href="contacts.php?service=<?php echo $service; ?>" class="btn btn-primary">Request This Service</a>
            </div>
        </div>
    </div>
</section>

<!-- Service Description -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold mb-6">About This Service</h2>
            <p class="text-gray-600 mb-10">
                <?php echo $current_service['full_description']; ?>
            </p>
            
            <h3 class="text-2xl font-bold mb-4">Key Features</h3>
            <ul class="space-y-3 mb-10">
                <?php foreach ($current_service['features'] as $feature): ?>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-primary mr-2 mt-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><?php echo $feature; ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
            
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h3 class="text-2xl font-bold mb-4">Ready to Get Started?</h3>
                <p class="mb-6">
                    Contact us today to schedule your <?php echo strtolower($current_service['title']); ?> service or learn more about how we can help with your bonsai needs.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="contacts.php?service=<?php echo $service; ?>" class="btn btn-primary">Contact Us</a>
                    <a href="services.php" class="btn bg-gray-200 text-gray-800 hover:bg-gray-300">View All Services</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Other Services -->
<section class="py-16">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-10 text-center">Explore Our Other Services</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            $count = 0;
            foreach ($services as $key => $other_service) {
                if ($key != $service && $count < 3) {
                    echo '<div class="bg-white rounded-lg shadow-lg overflow-hidden">';
                    echo '<img src="' . $other_service['image'] . '" alt="' . $other_service['title'] . '" class="w-full h-48 object-cover">';
                    echo '<div class="p-6">';
                    echo '<h3 class="text-xl font-bold mb-2">' . $other_service['title'] . '</h3>';
                    echo '<p class="text-gray-600 mb-4">' . $other_service['short_description'] . '</p>';
                    echo '<a href="single-service.php?service=' . $key . '" class="text-primary font-semibold hover:underline inline-flex items-center">';
                    echo 'Learn more';
                    echo '<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">';
                    echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
                    echo '</svg>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                    $count++;
                }
            }
            ?>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?> 