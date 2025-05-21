/**
 * Test script for order history functionality
 * 
 * This script tests the order history functionality by:
 * 1. Mocking the API response for orders
 * 2. Rendering the orders in the UI
 * 3. Verifying that the orders are displayed correctly with images, names, prices, and quantities
 */

// Mock API response
const mockOrders = [
    {
        id: 1001,
        user_id: 1,
        status: 'pending',
        created_at: new Date().toISOString(),
        total_amount: 450,
        item_count: 2,
        can_cancel: true,
        items: [
            { 
                quantity: 1, 
                dish_name: 'Butter Chicken', 
                price: 250,
                image: 'https://source.unsplash.com/random/100x100/?butter,chicken'
            },
            { 
                quantity: 2, 
                dish_name: 'Naan', 
                price: 100,
                image: 'https://source.unsplash.com/random/100x100/?naan'
            }
        ],
        booking_date: new Date().toLocaleDateString(),
        delivery_slot: '12:00 PM - 2:00 PM',
        payment_method: 'cash'
    },
    {
        id: 1002,
        user_id: 1,
        status: 'confirmed',
        created_at: new Date(Date.now() - 43200000).toISOString(), // 12 hours ago
        total_amount: 380,
        item_count: 3,
        can_cancel: false,
        items: [
            { 
                quantity: 1, 
                dish_name: 'Chicken Curry', 
                price: 220,
                image: 'https://source.unsplash.com/random/100x100/?chicken,curry'
            },
            { 
                quantity: 2, 
                dish_name: 'Roti', 
                price: 60,
                image: 'https://source.unsplash.com/random/100x100/?roti'
            },
            { 
                quantity: 1, 
                dish_name: 'Raita', 
                price: 40,
                image: 'https://source.unsplash.com/random/100x100/?raita'
            }
        ],
        booking_date: new Date().toLocaleDateString(),
        delivery_slot: '7:00 PM - 9:00 PM',
        payment_method: 'wallet'
    },
    {
        id: 1003,
        user_id: 1,
        status: 'delivered',
        created_at: new Date(Date.now() - 172800000).toISOString(), // 2 days ago
        total_amount: 350,
        item_count: 2,
        can_cancel: false,
        items: [
            { 
                quantity: 1, 
                dish_name: 'Paneer Tikka', 
                price: 200,
                image: 'https://source.unsplash.com/random/100x100/?paneer,tikka'
            },
            { 
                quantity: 1, 
                dish_name: 'Jeera Rice', 
                price: 150,
                image: 'https://source.unsplash.com/random/100x100/?jeera,rice'
            }
        ],
        booking_date: new Date(Date.now() - 172800000).toLocaleDateString(),
        delivery_slot: '7:00 PM - 9:00 PM',
        payment_method: 'wallet'
    }
];

// Function to run the test
function testOrderHistory() {
    console.log('Running order history test...');
    
    // Mock the getOrders function to return our mock data
    window.getOrders = function() {
        console.log('Mocked getOrders called');
        return Promise.resolve(mockOrders);
    };
    
    // Call loadOrders to render the orders
    loadOrders();
    
    // Log success message
    console.log('Test completed. Check the UI to verify that orders are displayed correctly.');
    console.log('Verify that:');
    console.log('1. Each order shows the first item\'s image');
    console.log('2. Each order shows all items with their names, quantities, and prices');
    console.log('3. Each order shows the total amount and payment method');
    console.log('4. The order details modal shows all items with their images');
}

// Add a button to run the test
$(document).ready(function() {
    // Add test button to the orders screen
    $('#orders-screen .app-header .header-actions').prepend(`
        <button class="header-action-btn me-2" id="test-orders-btn" title="Test Orders">
            <i class="fas fa-vial"></i>
        </button>
    `);
    
    // Add event listener for test button
    $('#test-orders-btn').on('click', function() {
        testOrderHistory();
    });
});
