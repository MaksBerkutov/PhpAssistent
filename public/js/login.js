document.addEventListener('DOMContentLoaded', function () {
    const tabLinks = Array.from(document.querySelectorAll('#pills-tab .nav-link'));
    const panes = Array.from(document.querySelectorAll('#pills-tabContent .tab-pane'));

    if (!tabLinks.length || !panes.length) {
        return;
    }

    function activateTab(link) {
        tabLinks.forEach(function (tabLink) {
            tabLink.classList.remove('active');
            tabLink.setAttribute('aria-selected', 'false');
        });

        panes.forEach(function (pane) {
            pane.classList.remove('show', 'active');
        });

        link.classList.add('active');
        link.setAttribute('aria-selected', 'true');

        const selector = link.getAttribute('data-bs-target') || link.getAttribute('href');
        if (!selector || !selector.startsWith('#')) {
            return;
        }

        const targetPane = document.querySelector(selector);
        if (targetPane) {
            targetPane.classList.add('show', 'active');
        }
    }

    tabLinks.forEach(function (link) {
        link.addEventListener('click', function (event) {
            const href = link.getAttribute('href');
            if (href && href.startsWith('#')) {
                event.preventDefault();
            }

            activateTab(link);
        });
    });

    const activeLink = tabLinks.find(function (link) {
        return link.classList.contains('active');
    });

    activateTab(activeLink || tabLinks[0]);
});
