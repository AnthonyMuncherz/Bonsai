# Sejuta Ranting Bonsai Website

A modern, responsive website for Sejuta Ranting, a Malaysian bonsai business, built with PHP, Tailwind CSS, and TypeScript.

## Technologies Used

- PHP for server-side rendering
- Tailwind CSS for styling
- TypeScript for enhanced JavaScript functionality
- Webpack for asset bundling

## Setup and Installation

### Prerequisites

- Laragon or similar local server environment (XAMPP, WAMP, etc.)
- Node.js and npm

### Installation Steps

1. Clone this repository into your Laragon/www directory
2. Install dependencies:
   ```
   npm install
   ```
3. Build assets:
   ```
   npm run build
   ```
4. Open the website through Laragon or by navigating to http://bonsai.test if you have configured your local domain.

## Development

To work on the website in development mode with automatic rebuilding:

```
npm run dev
```

## Project Structure

- `assets/`: Contains all CSS, JavaScript, and images
  - `css/`: Tailwind CSS styles
  - `js/src/`: TypeScript source files
  - `js/dist/`: Compiled JavaScript
  - `images/`: Website images
- `components/`: Reusable PHP components
- `includes/`: Header, footer, and other common includes
- `*.php`: Main page files

## File Descriptions

### Root PHP Files

- `about.php`: Displays the 'About Us' page, providing information about Sejuta Ranting, its mission, and its team.
- `activity_history.php`: Shows the logged-in user's activity history (e.g., book views, cart additions, order placements) with pagination. Requires login.
- `add_book.php`: (Admin) Provides a form for administrators to add new books to the catalogue, including details like title, author, description, price, stock, category, publisher, ISBN, pages, and image upload. Requires admin login.
- `add_to_cart.php`: Handles adding books to the shopping cart. Processes POST requests, checks stock, updates quantities if item exists, adds new item if not, and records user activity. Responds with JSON for AJAX requests. Requires login.
- `add_to_wishlist.php`: Handles adding or removing books from the user's wishlist (toggle functionality). Processes POST requests, checks if book exists, and records user activity. Responds with JSON for AJAX requests. Requires login.
- `admin.php`: (Deprecated) An old admin dashboard page. Now immediately redirects to `admin-dashboard.php`.
- `admin-books.php`: (Admin) Displays the book inventory management interface. Allows administrators to view all books, search/filter by title, author, description, or category, see stock levels, add new books, edit existing books, and delete books. Shows statistics like total books, low stock, and out of stock counts. Requires admin login.
- `admin-dashboard.php`: (Admin) Main dashboard for administrators. Shows overview statistics (users, books, orders), recent orders, low stock items, recent user activity, and links to other admin management pages. Requires admin login.
- `admin-orders.php`: (Admin) Displays the order management interface. Allows administrators to view all orders, filter by status (Pending, Processing, Shipped, Delivered, Cancelled), update order statuses, and view order details. Shows order statistics. Requires admin login.
- `admin-users.php`: (Admin) Displays the user management interface. Allows administrators to view all users, search users, add new users, edit user details (username, email, admin status), and delete users (except their own account). Handles AJAX requests for editing and deleting. Requires admin login.
- `book.php`: Displays the details page for a specific book identified by an ID in the GET parameter. Shows book image, title, author, description, price, stock status, publisher details, and action buttons (Add to Cart, Add to Wishlist). Also displays related books. Records book view activity if user is logged in.
- `cart.php`: Displays the user's shopping cart. Shows items, quantities, prices, and totals. Allows users to update quantities, remove items, and proceed to checkout. Requires login.
- `catalogue.php`: Displays the main book catalogue page. Shows a grid of books with images, titles, authors, prices, descriptions, and stock status. Allows filtering by category, searching, adding items to cart, and adding/removing items from wishlist. Includes admin links to add/manage books. Requires login.
- `change_password.php`: Provides a form for logged-in users to change their account password after verifying their current password. Requires login.
- `checkout.php`: Handles the checkout process. Displays the cart summary, allows users to enter a new shipping address or select an existing one, choose a payment method, and add order notes. On submission, it creates the order, order items, updates stock, updates/creates shipping address, clears the cart, records user activity, and redirects to the payment page. Requires login and items in the cart.
- `contacts.php`: Displays the contact page with business address, contact details (phone, email, WhatsApp), business hours, social media links, a contact form (currently non-functional), and an embedded Google Map.
- `dashboard.php`: Displays the main account dashboard for regular logged-in users. Shows account details, recent activity, recent orders, and quick links to other account sections (Orders, Wishlist, Edit Account, etc.). Redirects administrators to `admin-dashboard.php`. Requires login.
- `documentary.php`: Displays a dedicated page for the bonsai documentary, featuring an embedded video player with custom controls (download button), information about bonsai, documentary highlights, and a call-to-action section.
- `download_book_images.php`: A utility script to download book cover images from a predefined list of external URLs and save them locally into the `Images/books/` directory. Intended for initial setup or maintenance.
- `edit_account.php`: Provides a form for logged-in users to edit their account information (username and email address). Includes validation to prevent using an email already associated with another account. Requires login.
- `edit_book.php`: (Admin) Provides a form for administrators to edit the details of an existing book, identified by an ID in the GET parameter. Pre-fills the form with current book data and handles the update submission. Requires admin login.
- `index.php`: The main homepage of the website. Displays a hero section, featured books/services using `service-card`, a section promoting the documentary, an 'About Us' summary, featured categories/projects using `project-card`, and a customer testimonial.
- `login.php`: Displays the login form (username/email and password) and handles user authentication against the database. On success, starts a session and redirects to the dashboard. Redirects already logged-in users.
- `logout.php`: Handles user logout. Destroys the current session and redirects the user to the homepage.
- `manage_books.php`: (Admin) Provides an interface for administrators to manage the book catalogue, allowing searching, filtering, editing, and deleting books. Similar to `admin-books.php` but potentially intended as a different view or was an earlier version. Requires admin login.
- `my_orders.php`: Displays a list of the logged-in user's past orders, showing order number, date, status, total amount, and a link to view order details. Includes pagination. Requires login.
- `order_confirmation.php`: Displays the order confirmation page shown after a successful payment. Shows a success message, order number, transaction ID (if provided), order details, shipping address, and a summary of items ordered. Requires login and a valid order ID via GET parameter.
- `order_details.php`: Displays the detailed information for a specific order, identified by an ID in the GET parameter. Shows order number, date, status, shipping address, payment method, order notes, and a detailed list of items in the order. Requires login and that the order belongs to the user.
- `payment.php`: Handles the payment step after checkout, identified by an order ID in the GET parameter. Displays the order summary and the relevant payment form (Credit Card, Bank Transfer, E-Wallet) based on the method chosen during checkout. Processes the (simulated) payment submission, updates the order status to 'processing', records user activity, and redirects to the order confirmation page. Requires login and a pending order.
- `portfolio.php`: Displays the book collection page, likely intended as a gallery or portfolio view. Includes category filtering buttons and displays books in a grid with pagination. Uses AJAX to dynamically load books based on selected category and page number without full page reloads.
- `process_activity_logs.php`: A background script designed to be triggered asynchronously (e.g., by JavaScript in the footer). It reads pending activity log `.json` files from the `logs/` directory, inserts them into the `user_activities` database table, and deletes the processed files. Uses file locking to prevent simultaneous execution.
- `register.php`: Displays the user registration form (username, email, password, confirm password) and handles new user account creation. Performs validation (required fields, email format, password length, password match) and checks if the username or email already exists before inserting the new user into the database.
- `services.php`: Displays the 'Services' page, outlining additional offerings beyond book sales, such as book consultations, special orders, subscriptions, workshops, author events, forums, and digital resources. Uses `service-card` component potentially.
- `single-service.php`: Displays a detailed page for a specific book category or featured book, determined by the `service` GET parameter. Shows detailed information about the book (image, description, author, publisher, ISBN, pages, price) and includes 'Add to Cart' and 'Inquire' buttons. Also shows related books.
- `update_books.php`: A utility script to insert a predefined list of new book records into the `books` database table. Contains hardcoded book data. Intended for initial setup or adding sample data.
- `update_db.php`: A utility script primarily used for initial database setup and schema updates. Checks for the existence of tables (books, cart, wishlist, user_activities, orders, order_items, shipping_addresses) and creates them if they don't exist. Adds new columns to the `books` table (publisher, isbn, etc.) if missing. Updates sample book records with additional details. Creates the default admin user if the `users` table is newly created.
- `update_db_structure.php`: A utility script specifically focused on updating the `books` table schema. Adds the `publisher`, `isbn`, `pages`, and `published_year` columns if they don't already exist. It then updates existing book records based on title matching with hardcoded data for these new fields.
- `verify_book_data.php`: A simple utility script that fetches all records from the `books` table and displays them in an HTML table format. Useful for checking the database contents, especially after running update scripts.
- `wishlist.php`: Displays the logged-in user's wishlist. Shows a grid of books the user has added, including image, title, author, price, and stock status. Provides buttons to remove items from the wishlist or add them directly to the cart. Requires login.

### `components/` PHP Files

- `breadcrumbs.php`: Contains the `breadcrumbs()` function which generates the HTML for a breadcrumb navigation trail, typically displayed at the top of a page under the main header. It takes an array of page titles and URLs and an optional background image.
- `gallery.php`: Contains the `gallery()` function to generate HTML for an image gallery. It can create either a standard grid or a masonry layout based on the parameters provided (images array, type, columns).
- `project-card.php`: Contains the `project_card()` function to generate HTML for displaying a project or category showcase card. These cards typically include an image, title, description, and details like location/type, often used on the index or portfolio/collection pages.
- `service-card.php`: Contains the `service_card()` function to generate HTML for displaying a service or featured book card. These cards usually feature an image, title, description, a number, and a link, commonly used on the index or services pages.

### `includes/` PHP Files

- `db.php`: Handles the SQLite database connection and initial setup. Defines the `get_db_connection()` function which returns a configured SQLite3 object, creating the database file and schema (users, books, cart, wishlist, activities, orders, etc.) if they don't exist. Also inserts default admin user and sample books on initial creation. Includes the `user_exists()` helper function and requires `functions.php`.
- `footer.php`: Contains the closing HTML structure for pages, including the main site footer with address, social links, and copyright information. It includes script tags for `main.bundle.js` and AOS. It also contains JavaScript logic to trigger `process_activity_logs.php` asynchronously after the page loads if pending logs exist.
- `functions.php`: Contains common helper functions used across the site. Key functions include `record_user_activity()` which writes user activity to temporary JSON files in the `logs/` directory, `get_recent_user_activities()` which retrieves formatted activities from the database, and `process_activity_logs()` which reads the JSON log files and inserts them into the database (intended for background execution).
- `header.php`: Contains the opening HTML structure (`<!DOCTYPE>`, `<html>`, `<head>`) including meta tags, the page title, linking CSS (`styles.bundle.js`, Google Fonts, AOS), and inline styles. It also contains the main site header (`<header>`) with the logo and the primary navigation menu (both desktop and mobile versions), including logic for user/login status and dropdown menus.

### Root Configuration Files (JS/JSON)

- `tailwind.config.js`: Configures the Tailwind CSS framework. Defines theme customizations (colors like `primary`, `secondary`, fonts like `Mulish`, `Volkhov`), specifies which files Tailwind should scan for used classes (`content`), and potentially includes Tailwind plugins.
- `webpack.config.js`: Configures the Webpack module bundler. Defines the entry point (`assets/js/src/main.ts`), the output file (`assets/dist/main.bundle.js`), rules for processing different file types (e.g., using `ts-loader` for TypeScript, `style-loader`/`css-loader`/`postcss-loader` for CSS), and plugins like `MiniCssExtractPlugin` to extract CSS into a separate file (`styles.bundle.js`).
- `tsconfig.json`: Configures the TypeScript compiler (`tsc`). Specifies compiler options like the target JavaScript version (`ES2016`), module system (`ES6`), enabling strict type checking, and defining the output directory (`assets/js/dist/`) and the root source directory (`assets/js/src/`).
- `postcss.config.js`: Configures PostCSS, a tool for transforming CSS with JavaScript plugins. Specifies the use of the `tailwindcss` plugin (to process Tailwind directives and functions) and `autoprefixer` (to automatically add vendor prefixes for CSS rules).

### `assets/js/src/` TypeScript Files

- `main.ts`: The primary TypeScript file for frontend interactivity, compiled by Webpack into `assets/dist/main.bundle.js`. It initializes the AOS (Animate On Scroll) library, handles the mobile menu toggle functionality, implements smooth scrolling for anchor links, updates the copyright year dynamically, sets up parallax effects for specific elements, adds sticky behavior to the header on scroll, and manages the image gallery modal (opening/closing).

## Author

Shazriq Azrin bin Senawi

## License

[ISC License](LICENSE) 