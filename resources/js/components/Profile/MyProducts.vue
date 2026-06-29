<template>
    <div class="products-page">
      <nav class="profile-nav">
        <router-link to="/order" class="nav-link">
          <i class="fas fa-home"></i> Home
        </router-link>
        <router-link to="/profile" class="nav-link">
          <i class="fas fa-user"></i> My Profile
        </router-link>
        <router-link to="/profile-settings" class="nav-link">
          <i class="fas fa-cog"></i> Settings
        </router-link>
      </nav>
  
      <div class="products-container">
        <div class="header">
          <h2>My Products</h2>
          <button @click="showAddProductModal = true" class="add-btn">
            <i class="fas fa-plus"></i> Add New Product
          </button>
        </div>
  
        <div class="products-grid">
          <div v-for="product in products" :key="product.id" class="product-card">
            <img :src="product.image" :alt="product.name" class="product-image">
            <div class="product-info">
              <h3>{{ product.name }}</h3>
              <p class="price">₱{{ product.price }}</p>
              <p class="seller">Seller: {{ product.seller_name }}</p>
              <button @click="deleteProduct(product.id)" class="delete-btn">
                <i class="fas fa-trash"></i> Delete
              </button>
            </div>
          </div>
        </div>
  
        <!-- Add Product Modal -->
        <div v-if="showAddProductModal" class="modal">
          <div class="modal-content">
            <h3>Add New Product</h3>
            <form @submit.prevent="addProduct" class="add-product-form">
              <div class="form-group">
                <label for="productName">Product Name</label>
                <input 
                  type="text" 
                  id="productName"
                  v-model="newProduct.name" 
                  required
                />
              </div>
  
              <div class="form-group">
                <label for="productPrice">Price</label>
                <input 
                  type="number" 
                  id="productPrice"
                  v-model="newProduct.price" 
                  step="0.01"
                  required
                />
              </div>
  
              <div class="form-group">
                <label for="productImage">Product Image</label>
                <input 
                  type="file" 
                  id="productImage"
                  @change="handleImageUpload"
                  accept="image/*"
                  required
                />
              </div>
  
              <div class="modal-buttons">
                <button type="submit" class="save-btn">Add Product</button>
                <button type="button" @click="showAddProductModal = false" class="cancel-btn">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'MyProducts',
    data() {
      return {
        products: [],
        showAddProductModal: false,
        newProduct: {
          name: '',
          price: '',
          image: null
        }
      }
    },
    methods: {
      async fetchProducts() {
        try {
          const response = await axios.get('/my-products');
          this.products = response.data;
        } catch (error) {
          console.error('Error fetching products:', error);
        }
      },
      handleImageUpload(event) {
        this.newProduct.image = event.target.files[0];
      },
      async addProduct() {
        const formData = new FormData();
        formData.append('name', this.newProduct.name);
        formData.append('price', this.newProduct.price);
        formData.append('image', this.newProduct.image);
  
        try {
          await axios.post('/products', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
          });
          this.showAddProductModal = false;
          this.fetchProducts();
          this.newProduct = { name: '', price: '', image: null };
        } catch (error) {
          console.error('Error adding product:', error);
          alert('Error adding product. Please try again.');
        }
      },
      async deleteProduct(productId) {
        if (!confirm('Are you sure you want to delete this product?')) return;
  
        try {
          await axios.delete(`/products/${productId}`);
          this.fetchProducts();
        } catch (error) {
          console.error('Error deleting product:', error);
          alert('Error deleting product. Please try again.');
        }
      }
    },
    mounted() {
      this.fetchProducts();
    }
  }
  </script>
  
  <style scoped>
  .products-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }
  
  .profile-nav {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
  }
  
  .nav-link {
    text-decoration: none;
    color: #333;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
  }
  
  .nav-link:hover {
    background-color: #f0f0f0;
  }
  
  .products-container {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
  }
  
  .add-btn {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  
  .products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
  }
  
  .product-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
  }
  
  .product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
  }
  
  .product-info {
    padding: 15px;
  }
  
  .product-info h3 {
    margin: 0 0 10px 0;
  }
  
  .price {
    font-size: 18px;
    font-weight: bold;
    color: #4CAF50;
  }
  
  .seller {
    color: #666;
    font-size: 14px;
  }
  
  .delete-btn {
    background-color: #ff4444;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    margin-top: 10px;
    width: 100%;
  }
  
  .modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .modal-content {
    background: white;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
  }
  
  .add-product-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .modal-buttons {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
  }
  
  .save-btn, .cancel-btn {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  .save-btn {
    background-color: #4CAF50;
    color: white;
  }
  
  .cancel-btn {
    background-color: #666;
    color: white;
  }
  </style> 