// Amazon Clone - Main JavaScript File
const API_BASE_URL = 'http://localhost:8000/api';

// Global state
let cart = JSON.parse(localStorage.getItem('cart')) || [];
let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
let authToken = localStorage.getItem('authToken');
let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
let currentProducts = [];
let orders = [];

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    loadPageContent();
    setupEventListeners();
});

function loadPageContent() {
    const page = getCurrentPage();
    switch(page) {
        case 'index': loadHomePage(); break;
        case 'product': loadProductDetail(); break;
        case 'cart': loadCartPage(); break;
        case 'orders': loadOrdersPage(); break;
        case 'admin': loadAdminPage(); break;
        case 'wishlist': loadWishlistPage(); break;
    }
}

function getCurrentPage() {
    const path = window.location.pathname;
    if (path.includes('product.html')) return 'product';
    if (path.includes('cart.html')) return 'cart';
    if (path.includes('orders.html')) return 'orders';
    if (path.includes('admin.html')) return 'admin';
    if (path.includes('wishlist.html')) return 'wishlist';
    return 'index';
}

// Event Listeners
function setupEventListeners() {
    const searchBtn = document.getElementById('searchBtn');
    const searchInput = document.getElementById('searchInput');
    
    if (searchBtn) searchBtn.addEventListener('click', performSearch);
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') performSearch();
        });
    }
    
    const authForm = document.getElementById('authForm');
    if (authForm) authForm.addEventListener('submit', handleAuth);
}

// Homepage Functions
async function loadHomePage() {
    showLoadingSpinner();
    try {
        await loadProducts();
        hideLoadingSpinner();
    } catch (error) {
        console.error('Error loading homepage:', error);
        hideLoadingSpinner();
    }
}

async function loadProducts(filters = {}) {
    try {
        const queryParams = new URLSearchParams(filters);
        const response = await fetch(`${API_BASE_URL}/products?${queryParams}`);
        const data = await response.json();
        
        currentProducts = data.data || data;
        displayProducts(currentProducts);
        updateProductCount(currentProducts.length);
    } catch (error) {
        console.error('Error loading products:', error);
        showError('Failed to load products');
    }
}

function displayProducts(products) {
    const grid = document.getElementById('productsGrid');
    if (!grid) return;
    
    if (!products || products.length === 0) {
        grid.innerHTML = '<p class="col-span-full text-center py-8">No products found</p>';
        return;
    }
    
    grid.innerHTML = products.map(product => createProductCard(product)).join('');
    addProductCardListeners();
}

function createProductCard(product) {
    const mainImage = product.images?.[0] || 'https://via.placeholder.com/300x300';
    const isInWishlist = wishlist.some(w => w.id === product.id);
    
    return `
        <div class="product-card bg-white rounded-lg shadow-md overflow-hidden cursor-pointer group" data-product-id="${product.id}">
            <div class="relative">
                <img src="${mainImage}" alt="${product.name}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform">
                ${product.stock < 5 && product.stock > 0 ? '<div class="absolute top-2 left-2 bg-orange-500 text-white px-2 py-1 text-xs rounded">Low Stock</div>' : ''}
                <button onclick="toggleWishlist(event, ${product.id})" class="absolute top-2 right-2 p-2 rounded-full bg-white shadow-md hover:bg-gray-100">
                    <svg class="w-5 h-5 ${isInWishlist ? 'text-red-500 fill-current' : 'text-gray-600'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <h3 class="font-semibold mb-2">${product.name}</h3>
                <div class="flex text-yellow-400 text-sm mb-2">★★★★☆</div>
                <div class="flex justify-between items-center mb-3">
                    <span class="text-xl font-bold text-red-600">$${product.price}</span>
                    <span class="text-sm ${product.stock > 0 ? 'text-green-600' : 'text-red-600'}">
                        ${product.stock > 0 ? 'In Stock' : 'Out of Stock'}
                    </span>
                </div>
                <button class="add-to-cart-btn w-full bg-amazon-orange text-black font-semibold py-2 rounded-lg hover:bg-yellow-500" 
                        data-product-id="${product.id}" data-product-name="${product.name}" 
                        data-product-price="${product.price}" data-product-image="${mainImage}"
                        ${product.stock === 0 ? 'disabled' : ''}>
                    ${product.stock === 0 ? 'Out of Stock' : 'Add to Cart'}
                </button>
            </div>
        </div>
    `;
}

function addProductCardListeners() {
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('.add-to-cart-btn')) {
                localStorage.setItem('selectedProductId', this.dataset.productId);
                window.location.href = 'product.html';
            }
        });
    });
    
    document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            addToCart({
                id: this.dataset.productId,
                name: this.dataset.productName,
                price: parseFloat(this.dataset.productPrice),
                image: this.dataset.productImage,
                quantity: 1
            });
        });
    });
}

// Product Detail Functions
async function loadProductDetail() {
    const productId = localStorage.getItem('selectedProductId');
    if (!productId) {
        window.location.href = 'index.html';
        return;
    }
    
    showLoadingSpinner();
    try {
        const response = await fetch(`${API_BASE_URL}/products/${productId}`);
        const data = await response.json();
        displayProductDetail(data.product || data);
        hideLoadingSpinner();
        document.getElementById('productDetail')?.classList.remove('hidden');
    } catch (error) {
        console.error('Error loading product:', error);
        hideLoadingSpinner();
    }
}

function displayProductDetail(product) {
    document.getElementById('productName').textContent = product.name;
    document.getElementById('productPrice').textContent = `$${product.price}`;
    document.getElementById('productDescription').textContent = product.description;
    
    const stockStatus = document.getElementById('stockStatus');
    if (product.stock > 0) {
        stockStatus.textContent = `In Stock (${product.stock} available)`;
        stockStatus.className = 'text-green-600 font-medium';
    } else {
        stockStatus.textContent = 'Out of Stock';
        stockStatus.className = 'text-red-600 font-medium';
    }
    
    const mainImage = document.getElementById('mainImage');
    if (product.images?.[0]) {
        mainImage.src = product.images[0];
        mainImage.alt = product.name;
    }
    
    setupProductButtons(product);
}

function setupProductButtons(product) {
    const addToCartBtn = document.getElementById('addToCartBtn');
    const quantitySelect = document.getElementById('quantitySelect');
    
    if (product.stock === 0) {
        addToCartBtn.disabled = true;
        addToCartBtn.textContent = 'Out of Stock';
    } else {
        addToCartBtn.onclick = () => {
            const quantity = parseInt(quantitySelect?.value || 1);
            addToCart({
                id: product.id,
                name: product.name,
                price: product.price,
                image: product.images?.[0] || '',
                quantity: quantity
            });
        };
    }
}

// Cart Functions
function addToCart(item) {
    const existingItem = cart.find(cartItem => cartItem.id === item.id);
    
    if (existingItem) {
        existingItem.quantity += item.quantity;
    } else {
        cart.push(item);
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    showNotification(`${item.name} added to cart!`);
}

function updateCartCount() {
    const cartCount = document.getElementById('cartCount');
    if (cartCount) {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        cartCount.textContent = totalItems;
    }
}

function loadCartPage() {
    const emptyCart = document.getElementById('emptyCart');
    const cartItems = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        emptyCart?.classList.remove('hidden');
        cartItems?.classList.add('hidden');
    } else {
        emptyCart?.classList.add('hidden');
        cartItems?.classList.remove('hidden');
        displayCartItems();
        updateCartSummary();
    }
}

function displayCartItems() {
    const cartItems = document.getElementById('cartItems');
    if (!cartItems) return;
    
    cartItems.innerHTML = cart.map(item => `
        <div class="flex items-center space-x-4 p-4 border rounded-lg">
            <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded">
            <div class="flex-1">
                <h3 class="font-semibold">${item.name}</h3>
                <p class="text-gray-600">$${item.price}</p>
            </div>
            <select onchange="updateCartQuantity('${item.id}', this.value)" class="px-3 py-1 border rounded">
                ${[1,2,3,4,5].map(num => `<option value="${num}" ${num === item.quantity ? 'selected' : ''}>${num}</option>`).join('')}
            </select>
            <div class="text-right">
                <p class="font-semibold">$${(item.price * item.quantity).toFixed(2)}</p>
                <button onclick="removeFromCart('${item.id}')" class="text-red-600 text-sm">Remove</button>
            </div>
        </div>
    `).join('');
}

function updateCartSummary() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const shipping = subtotal > 25 ? 0 : 5.99;
    const tax = subtotal * 0.08;
    const total = subtotal + shipping + tax;
    
    document.getElementById('summarySubtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('summaryShipping').textContent = shipping === 0 ? 'FREE' : `$${shipping.toFixed(2)}`;
    document.getElementById('summaryTax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('summaryTotal').textContent = `$${total.toFixed(2)}`;
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
    loadCartPage();
}

function updateCartQuantity(productId, quantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity = parseInt(quantity);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        updateCartSummary();
    }
}

// Search and Filter Functions
function performSearch() {
    const searchTerm = document.getElementById('searchInput')?.value || '';
    const category = document.getElementById('categorySelect')?.value || '';
    
    const filters = {};
    if (searchTerm) filters.search = searchTerm;
    if (category) filters.category = category;
    
    loadProducts(filters);
}

function filterByCategory(category) {
    document.getElementById('categorySelect').value = category;
    loadProducts({ category });
}

function applyFilters() {
    const sort = document.getElementById('sortSelect')?.value || '';
    const minPrice = document.getElementById('minPrice')?.value || '';
    const maxPrice = document.getElementById('maxPrice')?.value || '';
    const category = document.getElementById('categorySelect')?.value || '';
    const search = document.getElementById('searchInput')?.value || '';
    
    const filters = {};
    if (search) filters.search = search;
    if (category) filters.category = category;
    if (sort) filters.sort = sort;
    if (minPrice) filters.min_price = minPrice;
    if (maxPrice) filters.max_price = maxPrice;
    
    loadProducts(filters);
}

// Auth Functions
function toggleAuthModal() {
    const modal = document.getElementById('authModal');
    modal?.classList.toggle('hidden');
}

function handleAuth(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);
    
    // Mock authentication - in real app, make API call
    if (data.email && data.password) {
        authToken = 'mock-token-' + Date.now();
        localStorage.setItem('authToken', authToken);
        toggleAuthModal();
        showNotification('Logged in successfully!');
    }
}

// Utility Functions
function showLoadingSpinner() {
    document.getElementById('loadingSpinner')?.classList.remove('hidden');
    document.getElementById('productsGrid')?.classList.add('hidden');
}

function hideLoadingSpinner() {
    document.getElementById('loadingSpinner')?.classList.add('hidden');
    document.getElementById('productsGrid')?.classList.remove('hidden');
}

function showNotification(message, type = 'success') {
    const notification = document.getElementById('notification');
    const notificationText = document.getElementById('notificationText');
    
    if (notification && notificationText) {
        notificationText.textContent = message;
        notification.classList.remove('hidden');
        
        const content = notification.firstElementChild;
        content.className = `px-6 py-3 rounded-lg shadow-lg ${
            type === 'error' ? 'bg-red-500' : 
            type === 'warning' ? 'bg-yellow-500' : 
            type === 'info' ? 'bg-blue-500' : 'bg-green-500'
        } text-white`;
        
        setTimeout(() => {
            notification.classList.add('hidden');
        }, 3000);
    }
}

function updateProductCount(count) {
    const productCount = document.getElementById('productCount');
    if (productCount) {
        productCount.textContent = `${count} products found`;
    }
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Admin Functions (Placeholder)
function loadAdminPage() {
    if (!authToken) {
        window.location.href = 'index.html';
        return;
    }
    loadDashboard();
}

function loadDashboard() {
    // Mock dashboard data
    document.getElementById('totalProducts').textContent = '50';
    document.getElementById('totalOrders').textContent = '125';
    document.getElementById('totalUsers').textContent = '89';
    document.getElementById('totalRevenue').textContent = '$15,420.50';
}

function loadOrdersPage() {
    if (!authToken) {
        window.location.href = 'index.html';
        return;
    }
    
    // Mock orders data
    const orders = [
        {
            id: '12345',
            created_at: new Date().toISOString(),
            total_price: 299.99,
            status: 'pending',
            items: [{name: 'Sample Product', quantity: 2}]
        }
    ];
    
    displayOrders(orders);
}

function displayOrders(orders) {
    const container = document.getElementById('ordersContainer');
    if (!container || !orders.length) {
        document.getElementById('noOrders')?.classList.remove('hidden');
        return;
    }
    
    container.innerHTML = orders.map(order => `
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold">Order #${order.id}</h3>
                    <p class="text-sm text-gray-600">${formatDate(order.created_at)}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                    ${order.status.toUpperCase()}
                </span>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Items</p>
                    <p class="font-semibold">${order.items.length}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="font-bold text-red-600">$${order.total_price}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-semibold">${order.status}</p>
                </div>
            </div>
        </div>
    `).join('');
    
    container.classList.remove('hidden');
}

// ===== AUTHENTICATION FUNCTIONS =====

function initializeAuth() {
    updateAuthButton();
    if (authToken) {
        validateToken();
    }
}

function updateAuthButton() {
    const authButton = document.getElementById('authButton');
    if (authButton) {
        if (currentUser) {
            authButton.innerHTML = `
                <div class="relative group">
                    <span class="text-sm cursor-pointer">Hello, ${currentUser.name}</span>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden group-hover:block z-50">
                        <div class="py-1">
                            <a href="orders.html" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Orders</a>
                            <a href="wishlist.html" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your Wishlist</a>
                            ${currentUser.is_admin ? '<a href="admin.html" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Admin Panel</a>' : ''}
                            <button onclick="logout()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign Out</button>
                        </div>
                    </div>
                </div>
            `;
        } else {
            authButton.innerHTML = '<span class="text-sm">Sign In</span>';
            authButton.onclick = openAuthModal;
        }
    }
}

async function validateToken() {
    if (!authToken) return;
    
    try {
        const response = await fetch(`${API_BASE_URL}/user`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const userData = await response.json();
            currentUser = userData.user || userData;
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            updateAuthButton();
        } else {
            logout();
        }
    } catch (error) {
        console.error('Token validation failed:', error);
        logout();
    }
}

function openAuthModal() {
    document.getElementById('authModal')?.classList.remove('hidden');
}

function closeAuthModal() {
    document.getElementById('authModal')?.classList.add('hidden');
}

function showLoginForm() {
    document.getElementById('loginForm')?.classList.remove('hidden');
    document.getElementById('registerForm')?.classList.add('hidden');
}

function showRegisterForm() {
    document.getElementById('loginForm')?.classList.add('hidden');
    document.getElementById('registerForm')?.classList.remove('hidden');
}

async function handleLogin(event) {
    event.preventDefault();
    
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            authToken = data.token;
            currentUser = data.user;
            
            localStorage.setItem('authToken', authToken);
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            
            closeAuthModal();
            updateAuthButton();
            showNotification('Login successful!', 'success');
            
            // Reload page content if needed
            loadPageContent();
        } else {
            showNotification(data.message || 'Login failed', 'error');
        }
    } catch (error) {
        console.error('Login error:', error);
        showNotification('Login failed. Please try again.', 'error');
    }
}

async function handleRegister(event) {
    event.preventDefault();
    
    const name = document.getElementById('registerName').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    
    try {
        const response = await fetch(`${API_BASE_URL}/register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name, email, password })
        });
        
        const data = await response.json();
        
        if (response.ok) {
            authToken = data.token;
            currentUser = data.user;
            
            localStorage.setItem('authToken', authToken);
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            
            closeAuthModal();
            updateAuthButton();
            showNotification('Account created successfully!', 'success');
            
            // Reload page content if needed
            loadPageContent();
        } else {
            showNotification(data.message || 'Registration failed', 'error');
        }
    } catch (error) {
        console.error('Registration error:', error);
        showNotification('Registration failed. Please try again.', 'error');
    }
}

async function logout() {
    try {
        if (authToken) {
            await fetch(`${API_BASE_URL}/logout`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json'
                }
            });
        }
    } catch (error) {
        console.error('Logout error:', error);
    } finally {
        authToken = null;
        currentUser = null;
        localStorage.removeItem('authToken');
        localStorage.removeItem('currentUser');
        updateAuthButton();
        showNotification('Logged out successfully', 'info');
        
        // Redirect to home if on protected page
        if (window.location.pathname.includes('admin.html') || window.location.pathname.includes('orders.html')) {
            window.location.href = 'index.html';
        }
    }
}

// ===== WISHLIST FUNCTIONS =====

function getWishlist() {
    return JSON.parse(localStorage.getItem('wishlist')) || [];
}

function saveWishlist(wishlistData) {
    localStorage.setItem('wishlist', JSON.stringify(wishlistData));
    wishlist = wishlistData;
}

function addToWishlist(product) {
    const currentWishlist = getWishlist();
    const existingItem = currentWishlist.find(item => item.id === product.id);
    
    if (!existingItem) {
        currentWishlist.push(product);
        saveWishlist(currentWishlist);
        showNotification(`${product.name} added to wishlist!`, 'success');
        return true;
    }
    return false;
}

function removeFromWishlist(productId) {
    const currentWishlist = getWishlist();
    const filteredWishlist = currentWishlist.filter(item => item.id != productId);
    saveWishlist(filteredWishlist);
    return true;
}

function toggleWishlist(event, productId) {
    event.stopPropagation();
    
    const currentWishlist = getWishlist();
    const existingItem = currentWishlist.find(item => item.id == productId);
    
    if (existingItem) {
        removeFromWishlist(productId);
        showNotification('Removed from wishlist', 'info');
    } else {
        // Find product details
        const product = currentProducts.find(p => p.id == productId);
        if (product) {
            addToWishlist(product);
        }
    }
    
    // Refresh current page if on products page
    if (window.location.pathname.includes('index.html') || window.location.pathname === '/') {
        displayProducts(currentProducts);
    }
}

function clearUserWishlist() {
    saveWishlist([]);
}

function loadWishlistPage() {
    const currentWishlist = getWishlist();
    const loadingSpinner = document.getElementById('loadingSpinner');
    const emptyWishlist = document.getElementById('emptyWishlist');
    const wishlistContainer = document.getElementById('wishlistContainer');
    const wishlistItems = document.getElementById('wishlistItems');
    const wishlistCount = document.getElementById('wishlistCount');
    
    // Hide loading spinner
    loadingSpinner?.classList.add('hidden');
    
    if (currentWishlist.length === 0) {
        emptyWishlist?.classList.remove('hidden');
        wishlistContainer?.classList.add('hidden');
    } else {
        emptyWishlist?.classList.add('hidden');
        wishlistContainer?.classList.remove('hidden');
        
        if (wishlistCount) {
            wishlistCount.textContent = currentWishlist.length;
        }
        
        if (wishlistItems) {
            wishlistItems.innerHTML = currentWishlist.map(product => `
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img src="${product.images?.[0] || 'https://via.placeholder.com/300x300'}" 
                             alt="${product.name}" class="w-full h-48 object-cover">
                        <button onclick="removeFromWishlistPage(${product.id})" 
                                class="absolute top-2 right-2 p-2 rounded-full bg-white shadow-md hover:bg-gray-100">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold mb-2">${product.name}</h3>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xl font-bold text-red-600">$${product.price}</span>
                            <span class="text-sm ${product.stock > 0 ? 'text-green-600' : 'text-red-600'}">
                                ${product.stock > 0 ? 'In Stock' : 'Out of Stock'}
                            </span>
                        </div>
                        <div class="space-y-2">
                            <button onclick="addToCartFromWishlist(${product.id})" 
                                    class="w-full bg-amazon-orange text-black font-semibold py-2 rounded-lg hover:bg-yellow-500"
                                    ${product.stock === 0 ? 'disabled' : ''}>
                                ${product.stock === 0 ? 'Out of Stock' : 'Add to Cart'}
                            </button>
                            <button onclick="viewProductFromWishlist(${product.id})" 
                                    class="w-full border border-gray-300 text-gray-700 font-semibold py-2 rounded-lg hover:bg-gray-50">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }
    }
}

function viewProductFromWishlist(productId) {
    localStorage.setItem('selectedProductId', productId);
    window.location.href = 'product.html';
}

// ===== CHECKOUT AND ORDERS =====

async function proceedToCheckout() {
    if (!currentUser) {
        showNotification('Please sign in to continue with checkout', 'warning');
        openAuthModal();
        return;
    }
    
    if (cart.length === 0) {
        showNotification('Your cart is empty', 'warning');
        return;
    }
    
    const orderData = {
        items: cart.map(item => ({
            id: item.id,
            quantity: item.quantity
        })),
        total_price: cart.reduce((sum, item) => sum + (item.price * item.quantity), 0),
        shipping_address: 'Default Address', // In real app, get from form
        payment_method: 'cash_on_delivery'
    };
    
    try {
        const response = await fetch(`${API_BASE_URL}/orders`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            },
            body: JSON.stringify(orderData)
        });
        
        const data = await response.json();
        
        if (response.ok) {
            // Clear cart
            cart = [];
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            
            showNotification('Order placed successfully!', 'success');
            
            // Redirect to orders page
            setTimeout(() => {
                window.location.href = 'orders.html';
            }, 2000);
        } else {
            showNotification(data.message || 'Failed to place order', 'error');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        showNotification('Failed to place order. Please try again.', 'error');
    }
}

async function loadOrdersPage() {
    if (!currentUser) {
        window.location.href = 'index.html';
        return;
    }
    
    const loadingSpinner = document.getElementById('loadingSpinner');
    const noOrders = document.getElementById('noOrders');
    const ordersContainer = document.getElementById('ordersContainer');
    
    try {
        const response = await fetch(`${API_BASE_URL}/orders`, {
            headers: {
                'Authorization': `Bearer ${authToken}`,
                'Accept': 'application/json'
            }
        });
        
        loadingSpinner?.classList.add('hidden');
        
        if (response.ok) {
            const data = await response.json();
            orders = data.data || data;
            
            if (orders.length === 0) {
                noOrders?.classList.remove('hidden');
                ordersContainer?.classList.add('hidden');
            } else {
                noOrders?.classList.add('hidden');
                ordersContainer?.classList.remove('hidden');
                displayOrdersList(orders);
            }
        } else {
            showNotification('Failed to load orders', 'error');
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        loadingSpinner?.classList.add('hidden');
        showNotification('Failed to load orders', 'error');
    }
}

function displayOrdersList(ordersList) {
    const container = document.getElementById('ordersContainer');
    if (!container) return;
    
    container.innerHTML = ordersList.map(order => `
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold">Order #${order.id}</h3>
                    <p class="text-sm text-gray-600">${formatDate(order.created_at)}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-sm ${getStatusBadgeClass(order.status)}">
                    ${getStatusIcon(order.status)} ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <p class="text-sm text-gray-600">Items</p>
                    <p class="font-semibold">${order.items.length}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total</p>
                    <p class="font-bold text-red-600">$${order.total_price}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Status</p>
                    <p class="font-semibold">${order.status}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <button onclick="viewOrderDetails(${order.id})" 
                        class="bg-amazon-blue text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    View Details
                </button>
                ${order.status === 'pending' || order.status === 'processing' ? `
                    <button onclick="cancelOrder(${order.id})" 
                            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-200">
                        Cancel Order
                    </button>
                ` : ''}
                ${order.status === 'delivered' ? `
                    <button onclick="reorderItems(${order.id})" 
                            class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-200">
                        Reorder
                    </button>
                ` : ''}
            </div>
        </div>
    `).join('');
}

function filterOrdersByStatus(status) {
    const filteredOrders = status === 'all' ? orders : orders.filter(order => order.status === status);
    displayOrdersList(filteredOrders);
}