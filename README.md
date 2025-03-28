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

## Author

Shazriq Azrin bin Senawi

## License

[ISC License](LICENSE) 