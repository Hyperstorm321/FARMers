<template>
     <v-container class="pop mt-5">
        <v-layout row wrap>
            <v-flex md12 xs12 class="text-center">
               <h1>Products</h1>
            </v-flex>
            <v-row class="mt-10">
                <v-card v-for="product in products" :key="product.product_id" class="ml-5 mb-5">
                    <div class="cardProductImage">
                         <img :src="require(`../assets/storage/images/products/${product.product_img_path}`)" class="previewImage" width="300px" height="250px">
                    </div>
                    <div class="text-center mt-5 mb-5">
                        {{product.product_name}} <br>
                        ₱ {{product.price}}<br> <br>
                        <v-btn rounded class="primary mr-3">Details</v-btn>
                        <v-btn rounded class="success" @click="sendInquiries(product.product_id, product.seller_id)">Inquire</v-btn>
                    </div>
                </v-card>
            </v-row>
        </v-layout>
        <v-snackbar rounded="pill" color="success" :timeout="timeout" v-model="snackbar">      
           <span class="ml-12 pl-12">Inquries Sent !</span> 
        </v-snackbar>
    </v-container>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');
.pop{
    font-family: 'Poppins', sans-serif;
}
.cardProductImage {
    /* background-color: grey; */
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    width: 250px;
    height: 250px;
}

.previewImage {
  justify-content: center;
  align-items: center;
  width: 250px;
  height: 250px;
  border: 1px solid grey;
  border-radius: 5px;
  
}
.valsuccess{
    color: green;
}
</style>

<script>
import axios from 'axios'
axios.defaults.withCredentials = true
axios.defaults.baseURL = 'http://localhost:8000'

export default {
    data(){
        return{
            products:[],
            user_id:'',
            snackbar:false,
            timeout: 1000,
        }
    },
    mounted(){
        this.getProducts()
        this.getUserDetail()
    },
    methods:{
        getUserDetail(){
            axios.get('/api/user').then(response =>{
                this.user_id = response.data.user_id
            })
        },
        getProducts(){
            axios.get('/api/products').then(response =>{
                this.products = response.data
                console.log(response.data)
            })
        },
        sendInquiries(product_id,seller_id){
           axios.post('/api/inquiry', {
               product_id: product_id,
               seller_id: seller_id,
               buyer_id: this.user_id
           }).then(response => {
               this.snackbar = true
           }).catch(error => {
               console.log(error.response.data)
           })
        }
    }
}
</script>