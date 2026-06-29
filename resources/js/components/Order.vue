<template>
  <div class="main-content" :class="{ 'move-right': isCartOpen, 'move-left': isSidebarOpen }" id="main-content">
    <!-- Navigation Bar -->
    <nav>
      <div class="logo">Order Menu</div>
      <ul>
        <li><a href="#" @click="filterProducts('')">All Products</a></li>
        <li><a href="#" @click="filterProducts('McDonalds')">McDonalds</a></li>
        <li><a href="#" @click="filterProducts('Jollibee')">Jollibee</a></li>
        <li><a href="#" @click="filterProducts('Chowking')">Chowking</a></li>
        <li><a href="#" @click="filterProducts('Mang Inasal')">Mang Inasal</a></li>
        <li><a href="#" @click="filterProducts('Greenwich')">Greenwich</a></li>
        <li><a href="#" @click="filterProducts('Crispy King')">Crispy King</a></li>
      </ul>
    </nav>

    <!-- Header with Profile and Cart Icons -->
    <div class="container">
      <header>
        <div class="profile">
          <img src="images/admin.png" alt="Profile" @click="toggleSidebar" />
          <span class="profile-name">Welcome, {{ currentUser ? currentUser.name : 'Guest' }}</span>
        </div>
        <div class="shopping" @click="toggleCart">
          <img src="images/shopping.svg" alt="Cart" />
          <span class="quantity">{{ cart.length }}</span>
        </div>
      </header>
      
      <!-- Filter and Search Options -->
      <div class="filter-options">
        <input type="text" v-model="keyword" placeholder="Enter keyword" @keyup="searchProducts" />
        <p>ex. Jollibee, Mcdo, Mang Inasal, Crispy King, Greenwich, etc </p>
      </div>

      <!-- Product List -->
      <div class="list" id="product-list">
        <div v-for="product in filteredProducts" :key="product.id" class="item">
          <img :src="product.image" alt="product.name" />
          <h2>{{ product.name }}</h2>
          <p class="price">₱{{ product.price.toFixed(2) }}</p>
          <button @click="addToCart(product)">Add to Cart</button>
        </div>
      </div>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart" :class="{ active: isCartOpen }" id="cart">
  <h1>Cart</h1>
  <div class="listCart">
    <div v-for="(item, index) in cart" :key="item.id" class="item">
      <img :src="item.image" alt="item.name" />
      <div class="name">{{ item.name }}</div>
      <div class="price">₱{{ (item.price ).toFixed(2)}}</div>
      <div class="quantity-controls">
        <button @click="decreaseQuantity(index)">-</button>
        <span>{{ item.quantity }}</span>
        <button @click="increaseQuantity(index)">+</button>
      </div>
       <!-- Total Price Section -->
  <div class="total-price">Total: ₱{{ (item.price * item.quantity).toFixed(2) }}</div>
    </div>
    <div class="total-price">Total: ₱{{ cartTotal }}</div>
  </div>
  <div class="buttons">
    <div class="close" @click="toggleCart">CLOSE</div>
    <div class="checkOut" @click="checkout">Checkout</div>
  </div>
</div>


    <!-- Profile Sidebar -->
    <div class="sidebar" :class="{ active: isSidebarOpen }" id="sidebar">
      <a href="/profile">My Profile</a>
      <a href="/my-products">My Products</a>
      <a href="/profile-settings">Profile Settings</a>
      <a href="/login" class="logout-btn">Logout</a>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      products: [],  // Products array initialized
      cart: [],
      keyword: '',
      isCartOpen: false,
      isSidebarOpen: false,
      selectedBrand: '',
      currentUser: null
    };
  },
  computed: {
    filteredProducts() {
      const filterText = this.keyword.toUpperCase();
      return this.products.filter(product => {
        return (
          product.name.toUpperCase().includes(filterText) &&
          (this.selectedBrand === '' || product.brand === this.selectedBrand)
        );
      });
    },
    cartTotal() {
    return this.cart.reduce((total, item) => {
      return total + item.price * item.quantity;
    }, 0).toFixed(2);
  }
},
  methods: {
    toggleCart() {
      if (this.isSidebarOpen) this.isSidebarOpen = false;
      this.isCartOpen = !this.isCartOpen;
    },
    toggleSidebar() {
      if(this.isCartOpen) this.isCartOpen = false;
      this.isSidebarOpen = !this.isSidebarOpen;
    },
    searchProducts() {
      this.filterProducts(this.selectedBrand);
    },
    filteredProducts() {
    const filterText = this.keyword.toUpperCase();
    return this.products.filter(product => {
      const matchesKeyword = product.name.toUpperCase().includes(filterText);
      const matchesBrand = this.selectedBrand === '' || product.brand === this.selectedBrand;
      return matchesKeyword && matchesBrand;
    });
  },
    addToCart(product) {
      const itemInCart = this.cart.find(item => item.id === product.id);
      if (itemInCart) {
        itemInCart.quantity++;
      } else {
        this.cart.push({ ...product, quantity: 1 });
      }
      localStorage.setItem('cart', JSON.stringify(this.cart));  // Store updated cart in localStorage
    },
    increaseQuantity(index) {
      this.cart[index].quantity++;
    },
    decreaseQuantity(index) {
      if (this.cart[index].quantity > 1) {
        this.cart[index].quantity--;
      } else {
        this.cart.splice(index, 1);
      }
    },
    checkout() {
      localStorage.setItem('cart', JSON.stringify(this.cart));
      this.$router.push({ name: 'Checkout' });
    }
  },
  mounted() {
    fetch('products.json')
      .then(response => response.json())
      .then(products => {
        this.products = products;
      })
      .catch(error => {
        console.error('Error loading products:', error);
      });
  },
  created() {
    // Get user data from localStorage when component is created
    const userData = localStorage.getItem('user');
    if (userData) {
      this.currentUser = JSON.parse(userData);
    } else {
      // If no user is logged in, redirect to login
      this.$router.push('/login');
    }
  }
};
</script>


<style scoped>
        body {
            margin: 0;
            padding: 0;
            background-color: #E3E7E8;
            font-family: system-ui;
            overflow-x: hidden;
        }
        .container {
            width: 1000px;
            margin: auto;
            transition: 0.5s;
        }

        /* Navigation Bar Styles */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: red;
            padding: 38px;
        }
        nav .logo {
            color: #fff;
            font-size: 18px;
            font-weight: bold;
        }
        nav ul {
            list-style: none;
            display: flex;
            margin: 0;
            padding: 0;
        }
        nav ul li {
            margin-left: 20px;
        }
        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #E8BC0E;
        }

        .profile img {
            width:70px;
            border-radius: 40%;
            cursor: pointer;
        }
        .profile img:hover {
            transform: scale(1.05); /* Optional hover effect */
        }

        /* Existing Styles */
        header {
            display: grid;
            grid-template-columns: 1fr 50px;
            margin-top: 50px;
        }
        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile img {
            width: 40px;
            border-radius: 50%;
            cursor: pointer;
        }
        .profile-name {
            font-weight: bold;
        }
        .shopping {
            position: relative;
            text-align: right;
            cursor: pointer;
        }
        .shopping img {
            width: 40px;
            cursor: pointer;
        }
        .shopping img:hover { 
            opacity: 0.8; 
            transform: scale(1.05); /* Optional hover effect */
        }
        .shopping span {
            background: red;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            position: absolute;
            top: -5px;
            left: 80%;
            padding: 3px 10px;
        }
        .list {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            column-gap: 50px;
            row-gap: 20px;
            justify-content: center;
            margin-top: 100px;
        }
        
        .list .item {
            text-align: center;
            background-color: #DCE0E1;
            padding: 20px;
            box-shadow: 0 50px 50px #757676;
            letter-spacing: 1px;
            transition: transform 0.3s; /* Optional hover effect */
        }
        .list .item:hover {
            transform: scale(1.05); /* Optional hover effect */
        }
        .list .item img {
            width: 300px;
            height: 300px;
            object-fit: cover;
        }
        .list .item .title {
            font-weight: 6000px;
        }
        .list .item .price {
            margin: 10px;
        }
        .list .item button {
            background-color: #1C1F25;
            color: #fff;
            width: 100%;
            padding: 10px;
            cursor: pointer;
        }
        .list .item button:hover { 
            opacity: 0.8; 
        }
        .cart {
            color: #fff;
            position: fixed;
            top: 0;
            right: -100%; /* Initially hidden off-screen */
            width: 400px;
            height: 100vh;
            background-color: #0e0f11;
            display: grid;
            grid-template-rows: 50px 1fr 50px;
            gap: 20px;
            transition: right 0.3s ease; /* Matching transition duration with sidebar */
        }

        .cart.active {
            right: 0; /* Open cart */
        }

        .cart h1 {
            color: #E8BC0E;
            padding: 20px;
            margin: 0;
        }
        .cart .listCart .item {
            display: grid;
            grid-template-columns: 80px 2fr 150px;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            background-color: #E8BC0E;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cart .listCart img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }

        .cart .listCart .item .name {
            font-weight: bold;
            font-size: 1.2em;
            color: black;
        }

        .cart .listCart .item .price {
            color: black;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: center;
        }

        .quantity-controls button {
            padding: 5px 12px;
            font-size: 16px;
            cursor: pointer;
            background-color: #1C1F25;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .quantity-controls button:hover {
            background-color: red;
        }

        .quantity-controls span {
            font-weight: bold;
            font-size: 16px;
            color: black;
        }

        .cart .total-price {
            font-weight: bold;
            font-size: 1.2em;
            color: black;
            padding: 20px;
            text-align: right;
            background-color: #E8BC0E;
            border-radius: 10px;
            margin-bottom: 10px;
          }
        .cart .buttons {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            text-align: center;
        }
        .cart .buttons div {
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            cursor: pointer;
        }
        .cart .buttons .checkOut {
            background-color: #E8BC0E;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100%;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #111;
            transition: left 0.10s ease; /* Smooth transition for sidebar */
            padding-top: 60px;
        }
        .sidebar a {
            padding: 15px 25px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar.active {
            left: 0;
        }
        .logout-btn {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #cc0000;
        }
        .main-content {
            transition: transform 0.3s ease; /* Add transition for movement */
        }
       /* .main-content.move-left {
            transform: translateX(250px); /* Shift right to make space for the sidebar 
        } */

/* Transform the main content when the cart is open */
    /*    .main-content.move-right {
            transform: translateX(-400px); /* Shift left to make space for the cart 
        } */
        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 10px; /* Space between input and description */
            max-width: 300px;
            margin-left: -20px;
            margin-top: 20px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
 
          }

        .filter-options input {
          padding: 10px;
          font-size: 10px;
          border: 2px solid black;
          border-radius: 4px;
          outline: none;
          transition: border-color 0.3s ease;
        }

        .filter-options input:focus {
          border-color: red; /* Highlight the input on focus */
        }

        .filter-options p {
        font-size: 10px;
        color: black;
        margin-top: 5px;
        line-height: 1.5;
      }

      .filter-options input::placeholder {
        color: #aaa; /* Placeholder text color */
      }

</style>
