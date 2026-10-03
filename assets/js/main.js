document.addEventListener('DOMContentLoaded', function () {

    // Live search
    const searchToggle = document.querySelector('.search-toggle');
    const searchPanel = document.querySelector('.live-search');
    const searchClose = document.querySelector('.live-search-close');
    const searchInput = document.querySelector('#live-search-input');
    const searchResults = document.querySelector('#live-search-results');

    if (
        searchToggle &&
        searchPanel &&
        searchClose &&
        searchInput &&
        searchResults
    ) {
        const searchUrl = searchPanel.dataset.searchUrl;

        let searchTimer;
        let searchController;

        // Clear search results and messages
        function clearResults() {
            searchResults.replaceChildren();
        }

        // Show a message in the search results container
        function showMessage(message) {
            clearResults();

            const paragraph = document.createElement('p');
            paragraph.className = 'live-search-message';
            paragraph.textContent = message;

            searchResults.appendChild(paragraph);
        }

        // Close the search panel and reset its state
        function closeSearch() {
            searchPanel.hidden = true;
            searchToggle.setAttribute('aria-expanded', 'false');

            clearTimeout(searchTimer);

            if (searchController) {
                searchController.abort();
            }

            searchInput.value = '';
            clearResults();
        }

        // Create a search result element for an article
        function createResult(article) {
            const link = document.createElement('a');
            link.className = 'live-search-result';
            link.href = article.url;

            if (article.image) {
                const image = document.createElement('img');
                image.src = article.image;
                image.alt = '';
                image.loading = 'lazy';

                link.appendChild(image);
            }

            const content = document.createElement('span');
            content.className = 'live-search-result-content';

            const title = document.createElement('strong');
            title.textContent = article.title;

            content.appendChild(title);

            if (article.category) {
                const category = document.createElement('small');
                category.textContent = article.category;

                content.appendChild(category);
            }

            link.appendChild(content);

            return link;
        }

        // Perform the search with the given term
        async function performSearch(term) {
            if (searchController) {
                searchController.abort();
            }

            searchController = new AbortController();

            showMessage('Searching...');

            try {
                const url = new URL(searchUrl);
                url.searchParams.set('term', term);

                const response = await fetch(url, {
                    signal: searchController.signal
                });

                if (!response.ok) {
                    throw new Error('Search request failed');
                }

                const articles = await response.json();

                // Ignore results if the input has changed.
                if (
                    searchPanel.hidden ||
                    searchInput.value.trim() !== term
                ) {
                    return;
                }

                clearResults();

                if (!articles.length) {
                    showMessage('No articles found.');
                    return;
                }

                articles.forEach(function (article) {
                    searchResults.appendChild(createResult(article));
                });

            } catch (error) {
                if (error.name !== 'AbortError') {
                    showMessage('Something went wrong. Please try again.');
                }
            }
        }

        // Toggle the search panel visibility when the search button is clicked
        searchToggle.addEventListener('click', function () {
            if (!searchPanel.hidden) {
                closeSearch();
                return;
            }

            searchPanel.hidden = false;
            searchToggle.setAttribute('aria-expanded', 'true');

            searchInput.focus();
        });

        // Close the search panel when the close button is clicked
        searchClose.addEventListener('click', closeSearch);

        // Handle input events in the search field
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);

            if (searchController) {
                searchController.abort();
            }

            const term = searchInput.value.trim();

            if (term.length < 2) {
                clearResults();
                return;
            }

            // Wait until the user pauses typing.
            searchTimer = setTimeout(function () {
                performSearch(term);
            }, 300);
        });

        // Close the search panel when the Escape key is pressed
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !searchPanel.hidden) {
                closeSearch();
                searchToggle.focus();
            }
        });

        // Close the search panel when clicking outside of it
        document.addEventListener('click', function (event) {
            if (
                !searchPanel.hidden &&
                !searchPanel.contains(event.target) &&
                !searchToggle.contains(event.target)
            ) {
                closeSearch();
            }
        });
    }
    
    // Featured article interaction
    const latestCards = document.querySelectorAll('.latest-news-card');
    const featuredImageLink = document.querySelector('.featured-image-link');
    const featuredImage = document.querySelector('.featured-image');
    const featuredTitleLink = document.querySelector('.featured-title-link');
    const featuredDescription = document.querySelector('.featured-description');
    const featuredReadMore = document.querySelector('.featured-read-more');

    if (
        latestCards.length &&
        featuredImageLink &&
        featuredImage &&
        featuredTitleLink &&
        featuredDescription &&
        featuredReadMore
    ) {
        function updateFeaturedArticle(card) {
            const { title, description, image, url } = card.dataset;

            if (!image || !url) {
                return;
            }

            featuredImage.removeAttribute('srcset');
            featuredImage.removeAttribute('sizes');
            featuredImage.src = image;
            featuredImage.alt = title;

            featuredImageLink.href = url;
            featuredTitleLink.href = url;
            featuredTitleLink.textContent = title;
            featuredDescription.textContent = description;
            featuredReadMore.href = url;
        }

        latestCards.forEach(function (card) {
            card.addEventListener('mouseenter', function () {
                updateFeaturedArticle(card);
            });

            card.addEventListener('focusin', function () {
                updateFeaturedArticle(card);
            });
        });
    }

    // Mobile menu
    // Handles opening, closing, and accessibility for the mobile navigation menu
    const menuToggle = document.querySelector('.menu-toggle');
    const mainNavigation = document.querySelector('.main-navigation');

    if (menuToggle && mainNavigation) {

        function closeMobileMenu() {
            mainNavigation.classList.remove('is-open');
            menuToggle.setAttribute('aria-expanded', 'false');
        }

        // Open and close mobile menu
        menuToggle.addEventListener('click', function () {
            const isOpen = mainNavigation.classList.toggle('is-open');

            menuToggle.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        });

        // Close when clicking a navigation link
        const navigationLinks = mainNavigation.querySelectorAll('a');

        navigationLinks.forEach(function (link) {
            link.addEventListener('click', closeMobileMenu);
        });

        // Close when pressing Escape
        document.addEventListener('keydown', function (event) {
            if (
                event.key === 'Escape' &&
                mainNavigation.classList.contains('is-open')
            ) {
                closeMobileMenu();
                menuToggle.focus();
            }
        });

        // Close when clicking outside the menu
        document.addEventListener('click', function (event) {
            if (
                mainNavigation.classList.contains('is-open') &&
                !mainNavigation.contains(event.target) &&
                !menuToggle.contains(event.target)
            ) {
                closeMobileMenu();
            }
        });

        // Close the mobile menu when switching from mobile to desktop
        window.addEventListener('resize', function () {
            if (window.innerWidth > 767) {
                closeMobileMenu();
            }
        });
    }

    // Active navigation links
    const menuLinks = Array.from(
        document.querySelectorAll('.header-menu a')
    );

    // Map menu links to their corresponding sections on the page
    const sections = menuLinks
        .map(function (link) {
            const url = new URL(link.href, window.location.href);

            // Only track sections on the current page
            if (
                url.origin !== window.location.origin ||
                url.pathname.replace(/\/$/, '') !==
                    window.location.pathname.replace(/\/$/, '') ||
                !url.hash
            ) {
                return null;
            }

            const section = document.getElementById(
                decodeURIComponent(url.hash.substring(1))
            );

            return section ? { link, section } : null;
        })
        .filter(Boolean);

    if (!sections.length) {
        return;
    }

    // Update the active menu item based on scroll position
    function updateActiveMenu() {
        const header = document.querySelector('.site-header');
        const headerHeight = header ? header.offsetHeight : 0;
        const threshold = headerHeight + 80;

        let activeItem = null;

        sections.forEach(function (item) {
            if (item.section.getBoundingClientRect().top <= threshold) {
                activeItem = item;
            }
        });

        menuLinks.forEach(function (link) {
            const isActive = activeItem !== null &&
                activeItem.link === link;

            link.classList.toggle('is-active', isActive);

            if (isActive) {
                link.setAttribute('aria-current', 'location');
            } else if (link.getAttribute('aria-current') === 'location') {
                link.removeAttribute('aria-current');
            }
        });
    }

    // Optimize scroll handling
    let ticking = false;

    window.addEventListener('scroll', function () {
        if (!ticking) {
            window.requestAnimationFrame(function () {
                updateActiveMenu();
                ticking = false;
            });

            ticking = true;
        }
    }, { passive: true });

    window.addEventListener('resize', updateActiveMenu);

    updateActiveMenu();
});