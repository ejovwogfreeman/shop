<?php

// Check if a session is already started before calling session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the current URL path
$current_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define an array of pages that do not require authentication
$unprotected_pages = [
    'https://capitalstreamexchange.com/',
    'https://capitalstreamexchange.com/explore',
    'https://capitalstreamexchange.com/all_creators',
    'https://capitalstreamexchange.com/blogs',
    'https://capitalstreamexchange.com/contact',
    'https://capitalstreamexchange.com/register',
    'https://capitalstreamexchange.com/login',
    'https://capitalstreamexchange.com/forgot_password',
    'https://capitalstreamexchange.com/reset_password/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}',
    'https://capitalstreamexchange.com/blog/[a-zA-Z0-9_-]+',
    'https://capitalstreamexchange.com/artwork/[a-zA-Z0-9_-]+',
    'https://capitalstreamexchange.com/profile/[a-zA-Z0-9_-]+',
    'https://capitalstreamexchange.com/creator/[a-zA-Z0-9_-]+'
];


// Function to check if the current URL matches any unprotected pages
function is_unprotected_page($url, $unprotected_pages)
{
    foreach ($unprotected_pages as $page) {
        // Create regex pattern from the unprotected page definition
        $pattern = "/^" . str_replace('/', '\/', $page) . "$/";

        // Debug: Output the pattern and the URL
        error_log("Checking pattern: $pattern against URL: $url");

        // Check if the URL matches the pattern
        if (preg_match($pattern, $url)) {
            // Debug: Match found
            error_log("Match found: $url matches $pattern");
            return true;
        }
    }
    return false;
}

// Debug: Output the current URL
error_log("Current URL: $current_url");

// Check if the session 'user' is not set and the current URL is not in the array of unprotected pages
if (!isset($_SESSION['user']) && !is_unprotected_page($current_url, $unprotected_pages)) {
    // Debug: Redirection happening
    error_log("Redirecting to login: $current_url is not unprotected and user is not logged in.");
    header('Location: https://capitalstreamexchange.com/login');
    exit(); // Ensure the script stops executing after redirection
}
