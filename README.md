# Test Project

This project is a test assignment built on WordPress using the UnderStrap theme. It leverages WooCommerce and WPML for multilingual e-commerce functionality. The project dynamically displays products and provides AJAX filtering by categories and price options.

## Code Structure

- **home.php**  
  The main page template which includes:
  - Retrieval of URL filter parameters.
  - Dynamic fetching of product categories and products using `WP_Query`.
  - Display of AJAX filters and the product listing.

- **partials/product.php**  
  Template part for rendering individual product details (image, title, categories, description, and price information).

- **functions.php**  
  Contains theme functions including:
  - Enqueueing scripts and localization of variables (with nonce for AJAX).
  - AJAX callback function for filtering products.
  - Custom tax calculation function that computes product prices with or without tax.

- **/js/main.js**  
  Handles the front-end logic for:
  - AJAX filtering based on category and price selections.
  - Updating the URL to preserve filter values.
  - Listening to browser navigation events (popstate) to update filters accordingly.

## How It Works

1. **Dynamic Product Display**  
   The home page template uses `WP_Query` to fetch products filtered by the selected language and category. It displays complete product details, including price calculations that adjust based on whether tax is applied.

2. **AJAX Filtering**  
   Users interact with category and price filters on the left side:
   - When a filter option is clicked, an AJAX request is triggered to retrieve filtered products.
   - A preloader animation is shown while fetching data.
   - The selected filters are also updated in the URL (using `pushState`), preserving the state when the page is refreshed or shared.

3. **Tax Calculation**  
   A custom function calculates the product prices:
   - Computes tax amounts based on the current locale.
   - Adjusts the price display based on whether the user opts to view prices with or without tax.

## Future Improvements

- **Advanced Architecture**  
  Consider refactoring the code using an MVC pattern or an object-oriented approach to further separate logic from presentation. This could include:
  - Creating dedicated classes for AJAX handling and tax calculations.
  - Implementing REST API endpoints for more flexible integration with modern front-end frameworks.

- **Performance Enhancements**  
  - Implement pagination or lazy loading to handle larger product databases.
  - Utilize caching (e.g., transients) for tax calculations and product queries.

