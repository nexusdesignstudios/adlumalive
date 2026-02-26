
// Projects Logic
async function updateProjects() {
    try {
        const waitForContainer = () => new Promise(resolve => {
            const check = () => {
                // Try to find the container by ID 'work'
                // If not found, look for a section with an h2 containing "Work" or "Projects"
                let container = document.getElementById('work');
                
                if (!container) {
                    // Fallback: Find h2 with "Work" and get its parent
                    const headers = Array.from(document.querySelectorAll('h2'));
                    const workHeader = headers.find(h => h.textContent.includes('Work') || h.textContent.includes('Projects'));
                    if (workHeader) {
                        // Assume the section is the parent or the container is a sibling
                        // This is heuristic and might need adjustment
                        container = workHeader.closest('section') || workHeader.parentElement;
                    }
                }

                if (container) resolve(container);
                else requestAnimationFrame(check);
            };
            check();
        });

        const section = await waitForContainer();
        
        // Fetch projects from API (Only Featured)
        const response = await fetch('/api/projects?featured=true');
        if (!response.ok) throw new Error('Failed to fetch projects');
        const projects = await response.json();

        if (!projects || projects.length === 0) return;

        // Find or create the grid container
        // We want to replace the static content with dynamic content
        // But we want to keep the header (h2) if possible
        
        // Look for a grid container inside the section
        let grid = section.querySelector('.grid');
        
        // Check if this grid is the "bad" design div (grid-cols-5)
        if (grid && grid.className.includes('grid-cols-5')) {
            // It's the bad design div, let's remove it and create a fresh one
            grid.remove();
            grid = null;
        }

        if (!grid) {
            // If no grid found, maybe the section *is* the container?
            // Or create one
            grid = document.createElement('div');
            grid.className = 'grid md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12';
            section.appendChild(grid);
        } else {
            // Clear existing static projects
            grid.innerHTML = '';
            // Ensure correct classes
            grid.className = 'grid md:grid-cols-2 lg:grid-cols-3 gap-8 mt-12';
        }

        // Render projects
        projects.forEach(project => {
            const card = document.createElement('div');
            card.className = 'group relative overflow-hidden rounded-2xl bg-white/5 border border-white/10 hover:border-white/20 transition-all duration-300';
            
            const image = project.image_url || 'https://via.placeholder.com/600x400';
            
            card.innerHTML = `
                <div class="aspect-video w-full overflow-hidden">
                    <img src="${image}" alt="${project.title}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>
                <div class="p-6">
                    <div class="mb-2 text-xs font-medium uppercase tracking-wider text-orange-500">${project.category || 'Project'}</div>
                    <h3 class="mb-2 text-xl font-bold text-white">${project.title}</h3>
                    <p class="mb-4 text-sm text-gray-400 line-clamp-3">${project.description}</p>
                    ${project.link ? `
                    <a href="${project.link}" target="_blank" class="inline-flex items-center text-sm font-medium text-white hover:text-orange-500 transition-colors">
                        View Project <svg class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>` : ''}
                </div>
            `;
            
            grid.appendChild(card);
        });

    } catch (error) {
        console.error('Error loading projects:', error);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateProjects);
} else {
    updateProjects();
}
