(function() {
    // Only run on admin pages
    if (!window.location.href.includes('/admin') && !window.location.href.includes('/dashboard')) return;

    console.log('Admin Projects Script Loaded');

    let projectsTabInitialized = false;

    // Helper: Get Auth Headers
    function getAuthHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        // 1. Try XSRF-TOKEN for Sanctum cookie auth
        const getCookie = (name) => {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        };
        const xsrfToken = getCookie('XSRF-TOKEN');
        if (xsrfToken) {
            headers['X-XSRF-TOKEN'] = decodeURIComponent(xsrfToken);
        }

        // 2. Try Bearer Token (localStorage/sessionStorage)
        const findToken = () => {
            const candidates = ['token', 'auth_token', 'access_token', 'jwt', 'bearer'];
            for (const key of candidates) {
                if (localStorage.getItem(key)) return localStorage.getItem(key);
                if (sessionStorage.getItem(key)) return sessionStorage.getItem(key);
            }
            return null;
        };
        const token = findToken();
        if (token) {
            headers['Authorization'] = `Bearer ${token}`;
        }

        return headers;
    }

    function initProjectsTab() {
        if (projectsTabInitialized) return;
        
        // Find sidebar navigation
        // Heuristic: Look for "Dashboard" or "Logout" links
        const navLinks = Array.from(document.querySelectorAll('a, button'));
        const dashboardLink = navLinks.find(l => l.textContent.trim().includes('Dashboard') || l.textContent.trim().includes('Home'));
        
        if (!dashboardLink) return; 
        
        // Find the container of the link (usually <li> or just the <nav>)
        const sidebarNav = dashboardLink.closest('nav') || dashboardLink.closest('ul') || dashboardLink.parentElement.parentElement;
        
        if (!sidebarNav) return;

        // Check if Projects tab already exists
        if (Array.from(sidebarNav.querySelectorAll('a, button')).some(l => l.textContent.includes('Projects'))) {
            projectsTabInitialized = true;
            return;
        }

        console.log('Injecting Projects Tab...');

        // Clone the dashboard link to match styles
        const projectsLink = dashboardLink.cloneNode(true);
        // Clean up clone
        projectsLink.innerHTML = ''; 
        projectsLink.textContent = 'Projects';
        projectsLink.href = '#projects';
        
        // Attempt to keep icon structure if it exists
        if (dashboardLink.querySelector('svg')) {
             const icon = dashboardLink.querySelector('svg').cloneNode(true);
             projectsLink.prepend(icon); // Add icon back
             // Replace icon path if possible, or just leave as generic dashboard icon
        }

        // Ensure text is visible
        if (projectsLink.childNodes.length === 0) {
            projectsLink.textContent = 'Projects';
        }

        // Handle insertion
        if (dashboardLink.parentElement.tagName === 'LI') {
            const newLi = dashboardLink.parentElement.cloneNode(false);
            newLi.appendChild(projectsLink);
            sidebarNav.appendChild(newLi);
        } else {
            sidebarNav.appendChild(projectsLink);
        }

        projectsLink.addEventListener('click', (e) => {
            e.preventDefault();
            showProjectsView();
        });

        projectsTabInitialized = true;
    }

    function showProjectsView() {
        // Find main content area
        const main = document.querySelector('main') || document.querySelector('#root > div > div:last-child');
        if (!main) return;

        // Hide all direct children
        Array.from(main.children).forEach(c => c.style.display = 'none');

        // Create or Show Projects View
        let projectsView = document.getElementById('admin-projects-view');
        if (!projectsView) {
            projectsView = document.createElement('div');
            projectsView.id = 'admin-projects-view';
            projectsView.className = 'p-6 w-full';
            main.appendChild(projectsView);
        }
        projectsView.style.display = 'block';

        renderProjectsList(projectsView);
    }

    async function renderProjectsList(container) {
        container.innerHTML = `
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-white">Projects</h1>
                <button id="add-project-btn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    + Add Project
                </button>
            </div>
            <div id="projects-grid" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <div class="text-gray-400">Loading projects...</div>
            </div>
        `;

        container.querySelector('#add-project-btn').onclick = () => renderProjectForm(container);

        try {
            const response = await fetch('/api/projects', {
                headers: getAuthHeaders()
            });
            if (!response.ok) throw new Error('Failed to fetch projects');
            
            const projects = await response.json();
            const grid = container.querySelector('#projects-grid');
            grid.innerHTML = '';

            if (projects.length === 0) {
                grid.innerHTML = '<p class="text-gray-400 col-span-full">No projects found.</p>';
                return;
            }

            projects.forEach(p => {
                const card = document.createElement('div');
                card.className = 'bg-white/5 border border-white/10 p-4 rounded-xl flex flex-col gap-3 hover:border-white/20 transition';
                card.innerHTML = `
                    <div class="aspect-video w-full bg-black/20 rounded-lg overflow-hidden">
                        <img src="${p.image_url || 'https://via.placeholder.com/300'}" class="w-full h-full object-cover opacity-80" onerror="this.src='https://via.placeholder.com/300'">
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-white mb-1">${p.title}</h3>
                        <p class="text-xs text-blue-400 mb-2 uppercase tracking-wider">${p.category || 'Uncategorized'}</p>
                        <p class="text-sm text-gray-400 line-clamp-2">${p.description}</p>
                    </div>
                    <div class="flex gap-2 mt-2 pt-2 border-t border-white/5">
                        <button class="edit-btn flex-1 py-1.5 text-sm bg-white/5 hover:bg-white/10 rounded text-blue-300">Edit</button>
                        <button class="delete-btn flex-1 py-1.5 text-sm bg-red-500/10 hover:bg-red-500/20 rounded text-red-400">Delete</button>
                    </div>
                `;
                
                card.querySelector('.edit-btn').onclick = () => renderProjectForm(container, p);
                card.querySelector('.delete-btn').onclick = () => {
                    if(confirm('Are you sure you want to delete this project?')) {
                        deleteProject(p.id, () => renderProjectsList(container));
                    }
                };
                
                grid.appendChild(card);
            });

        } catch (err) {
            container.querySelector('#projects-grid').innerHTML = `<p class="text-red-500">Error: ${err.message}</p>`;
        }
    }

    function renderProjectForm(container, project = null) {
        const isEdit = !!project;
        container.innerHTML = `
            <div class="max-w-2xl mx-auto bg-white/5 border border-white/10 p-6 rounded-xl">
                <h2 class="text-xl font-bold text-white mb-6">${isEdit ? 'Edit Project' : 'New Project'}</h2>
                <form id="project-form" class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Title</label>
                        <input type="text" name="title" value="${project?.title || ''}" required 
                               class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Category</label>
                        <input type="text" name="category" value="${project?.category || ''}" 
                               class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Description</label>
                        <textarea name="description" rows="4" required 
                                  class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-blue-500 outline-none">${project?.description || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Image URL</label>
                        <input type="url" name="image_url" value="${project?.image_url || ''}" 
                               class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Project Link (Optional)</label>
                        <input type="url" name="link" value="${project?.link || ''}" 
                               class="w-full bg-black/20 border border-white/10 rounded px-3 py-2 text-white focus:border-blue-500 outline-none">
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" id="cancel-btn" class="px-4 py-2 text-gray-400 hover:text-white">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Project</button>
                    </div>
                </form>
            </div>
        `;

        container.querySelector('#cancel-btn').onclick = () => renderProjectsList(container);
        
        container.querySelector('#project-form').onsubmit = async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            
            const url = isEdit ? `/api/projects/${project.id}` : '/api/projects';
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: getAuthHeaders(),
                    body: JSON.stringify(data)
                });

                if (!response.ok) throw new Error('Failed to save project');
                
                renderProjectsList(container);
            } catch (err) {
                alert(`Error saving project: ${err.message}`);
            }
        };
    }

    async function deleteProject(id, callback) {
        try {
            const response = await fetch(`/api/projects/${id}`, {
                method: 'DELETE',
                headers: getAuthHeaders()
            });
            if (!response.ok) throw new Error('Failed to delete');
            callback();
        } catch (err) {
            alert(err.message);
        }
    }

    // Start Observer
    const observer = new MutationObserver(() => {
        initProjectsTab();
    });
    observer.observe(document.body, { childList: true, subtree: true });
    
    // Initial check
    initProjectsTab();

})();
