<template>
    <v-container class="pop mt-5">

        <v-layout row wrap>
            <v-flex md12 xs12 class="text-center">
               <h1>Products</h1>
            </v-flex>

            <v-flex md12 xs12 class="text-right">
                <v-dialog v-model="addProductDialog" max-width="800">
                    <template v-slot:activator="{ on, attrs }">
                    <v-btn rounded class="success"  v-bind="attrs" v-on="on"> <v-icon size="17" class="pr-1">mdi-plus-thick</v-icon> Add Product </v-btn>
                    </template>
                    <v-card class="rounded-xl">
                        
                        <v-card-title class="pt-5">Create Product</v-card-title>

                        <v-container>
                            <v-row align="start">
                                <v-col cols="12" md="6" xs="12">
                                    <v-card-text>
                                        <div class="previewImage">
                                            <img v-if="imageUrl" :src="imageUrl" />
                                        </div>
                                        <br>
                                        <input type="file" @change="previewInsertedImg" class="mb-12"/>
                                        <v-btn rounded class="success mb-5 mt-12" @click="createProduct">Add Product</v-btn>
                                        <div class="valsuccess">{{successAlert}}</div>
                                    </v-card-text>
                                </v-col>

                                <v-col cols="12" md="6" xs="12">
                                    <v-card-text>
                                        <v-text-field outlined v-model="product.product_name" label="Product Name" class="mt-3"></v-text-field>
                                        <v-text-field outlined v-model="product.price" label="Price" prefix="₱"></v-text-field>

                                        <v-row>
                                            <v-col>
                                                <v-text-field outlined v-model="product.total_stocks" label="Stocks"></v-text-field>
                                            </v-col>
                                            <v-col>
                                                <v-text-field outlined v-model="product.min_buyable_stocks" label="Min. Buyable Stocks"></v-text-field>
                                            </v-col>
                                        </v-row>

                                        <v-textarea outlined name="input-7-4" label="Description" v-model="product.description"></v-textarea>

                                        
                                    </v-card-text>
                                </v-col>
                            </v-row>
                        </v-container>

                        <!-- <v-card-actions class="justify-center">
                            <v-btn class="primary mb-5" @click="createProduct">Add Product</v-btn>
                        </v-card-actions> -->
                    </v-card>
                </v-dialog>
           </v-flex>

            <v-row class="mt-10">
                <v-card v-for="product in products" :key="product.product_id" class="ml-5 mb-5">
                    <div class="cardProductImage">
                         <img :src="require(`../../assets/storage/images/products/${product.product_img_path}`)" class="previewImage" width="300px" height="250px">
                    </div>
                    <div class="text-center mt-5 mb-5">
                        {{product.product_name}} <br>
                        ₱ {{product.price}}<br> <br>
                        <v-btn class="primary mr-5">Details</v-btn>
                        <v-btn class="error" @click="removeProduct(product.product_id)">Remove</v-btn> <br> <br>
                        <v-btn class="primary" @click.stop="dialog = true" @click="getInquiries(product.product_id)">View Offers</v-btn>
                        <v-dialog v-model="dialog" max-width="500" class="rounded-xl">
                            <v-card class="rounded-xl mx-auto" max-width="500">
                                <v-card-title class="headline"> Inquiries</v-card-title>
                                <v-card-text>
                                    <v-simple-table>
                                        <template v-slot:default>
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="inquiry in inquiries" :key="inquiry.id">
                                                    <td>{{ inquiry.first_name }} {{ inquiry.last_name }}</td>
                                                    <td>{{ inquiry.created_at.slice(0,10) }}</td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </v-simple-table>
                                </v-card-text>
                                <v-card-actions class="justify-center">
                                        <v-btn class="primary mb-5 pl-5 pr-5"  @click.stop="dialog = false">Close</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </div>
                </v-card>
            </v-row>

        </v-layout>
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
  display: flex;
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
.previewImage img {
  max-width: 100%;
  max-height: 100%;
}
.bordered {
    border: 1px solid black;
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
            inquiries:[],
            product:{
                product_name:'',
                description:'',
                product_img:null,
                price:0,
                total_stocks:'',
                min_buyable_stocks:''
            },
            user_id:'',
            imageUrl:null,
            product_img:null,
            addProductDialog:false,
            successAlert:'',
            dialog:false
        }
    },
    mounted(){
        this.getOwnProducts()
        this.getUserData()
    },
    methods:{
        getUserData(){
            axios.get('/api/user').then(response => {
                this.user_id = response.data.user_id
            })
        },
        getOwnProducts(){
            let id = this.user_id
             axios.get('/api/user').then(response =>{
                id = response.data.user_id
                axios.get(`/api/products/${id}`).then(response => {
                    this.products = response.data
                })
            })
        },
        removeProduct(product_id){
            axios.delete(`/api/product/${product_id}`).then(response => {
                this.getOwnProducts()
            })
        },
        createProduct(){

            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }
            let formData = new FormData()
            formData.append('product_img', this.product_img)
            formData.append('seller_id', this.user_id)
            formData.append('product_name', this.product.product_name)
            formData.append('description', this.product.description)
            formData.append('price', this.product.price)
            formData.append('total_stocks', this.product.total_stocks)
            formData.append('min_buyable_stocks', this.product.min_buyable_stocks)

            axios.post('/api/product', formData, config
            ).then(response => {
                // this.products.push({
                //     product_name: this.product.product_name,
                //     price : ,
                //     product_img:

                // })
                this.successAlert = 'Added Successfully'
                window.location.reload()
                // this.addProductDialog = false
            }).catch(err => {console.log(err)})
        },
        previewInsertedImg(e){
            const file = e.target.files[0]
            this.imageUrl = URL.createObjectURL(file)
            this.product_img = e.target.files[0]
        },
        getInquiries(product_id){
            axios.get(`/api/inquiry/${product_id}`).then(response =>{
                this.inquiries = response.data
            }).catch(error => {
                console.log(error.response.data)
            })
        }
    }
}
</script>