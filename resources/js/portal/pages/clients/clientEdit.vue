<template>

    <div class="row justify-content-center res">
        <div class="col-xxl-8">
            <form @submit.prevent="updateClient" id="clientEdit" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="fs-24">Edit Client</h1>
                    </div>
                    <div class="cl col-lg-12">
                        <div class="card">
                            <div class="card-body p-lg-5">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group mb-4 avatar">
                                            <div class="w-100">
                                                <img :src="clientLogo" height="130" width="130" alt="avatar" class="rounded-circle">
                                            </div>
                                            <div class="w-100">
                                                <input type="file" id="uploadLogo" class="form-control-custom d-none" name="logo"
                                                       @change="AttachFile($event)" accept="image/*"
                                                       autocomplete="new-file_path">
                                            </div>
                                            <div class="w-100 mt-2">
                                                <label for="uploadLogo" class="btn btn-sm btn-theme py-1 px-3 rounded-pill"><i class="fa fa-fw fa-upload"></i> Upload Photo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="name">Name</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   id="name" name="name" placeholder="Name"
                                                   v-model="formData.name">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" class="form-control form-control-lg"
                                                   id="email" name="email" placeholder="Email"
                                                   v-model="formData.email">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="email">Phone</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   id="phone" name="phone" placeholder="Phone"
                                                   autocomplete="new-phone" @keypress="checkNumber($event)" v-model="formData.phone">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="email">Address</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   id="address" name="address" placeholder="Address"
                                                   v-model="formData.address">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="email">City</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   id="city" name="city" placeholder="city"
                                                   v-model="formData.city">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="email">Country</label>
                                            <input type="text" class="form-control form-control-lg"
                                                   id="country" name="country" placeholder="Country"
                                                   v-model="formData.country">
                                            <div class="error-report text-danger "></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <router-link :to="{name: 'Clients'}" class="btn btn-outline-secondary px-3 me-2">Cancel</router-link>
                                <button type="submit" class="btn btn-theme px-3" v-if="loading === false">Update</button>
                                <button type="button" disabled v-if="loading === true"
                                        class="btn btn-theme px-3" style="width: 87px;">
                                    <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</template>
<script>

import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

import {createToaster} from "@meforma/vue-toaster";

const toaster = createToaster({
    position: 'top-right'
});

export default {
    components: {createToaster},
    data() {
        return {
            formData: {
                id: '',
                name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                country: '',
            },
            clientLogo: '/assets/images/profile.png',
            loading: false
        }
    },
    methods: {
        /*Get Client Data*/
        getSingle(id){
            apiService.POST(apiRoutes.clientSingle, {id} , (res) => {
                if (res.status === 200) {
                    this.formData = res.data;
                    this.clientLogo = res.data.logo_path ?? this.clientLogo;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Update Client*/
        updateClient(){
            apiService.ClearErrorHandler();
            this.loading = true;
            const formData = new FormData(document.getElementById('clientEdit'));
            formData.append('id', this.formData.id);
            apiService.POST_FORMDATA(apiRoutes.clientUpdate, formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    this.$router.push({name: 'Clients'})
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Upload Logo*/
        AttachFile: function (event) {
            let file = event.target.files[0];
            this.clientLogo = URL.createObjectURL(file);
        },

        /*number validation*/
        checkNumber(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                // @ts-ignore
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /^\d*\.?\d*$/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        },
    },
    mounted() {
        if(this.$route.params){
            const {id} = this.$route.params;
            this.getSingle(id)
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
