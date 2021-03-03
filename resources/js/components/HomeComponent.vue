<template>
  <div>
    <v-toolbar color="purple lighten-1" class="d-none d-md-flex d-lg-flex d-xl-none" dense>
      <v-toolbar-title>
        Media Uploader
      </v-toolbar-title>
    </v-toolbar>
    <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" @vdropzone-thumbnail="vfileAdded"></vue-dropzone>
    <v-btn block color="primary" @click="upload">Upload</v-btn>

    <v-container fluid>
      <v-row justify="space-around">
        <v-col cols="10">
          <div v-for="(image, index) in images" :key="index">
            <v-img :src="image" aspect-ratio="1.7" width="100"></v-img>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
  import vue2Dropzone from 'vue2-dropzone'
  import 'vue2-dropzone/dist/vue2Dropzone.min.css'
  export default {
    components: {
      vueDropzone: vue2Dropzone
    },
    data() {
      return {
        dropzoneOptions: {
          url: '/api/upload',
          thumbnailWidth: 150,
          maxFilesize: 1,
          uploadMultiple: true,
          autoProcessQueue: false,
          acceptedFiles: "image/png",
        },
        images: [],
        files: [],
        form: new Form({
          images: []
        })
      }
    },
    methods: {
      vfileAdded(file) {
        this.form.images.push(file.dataURL);
        this.files.push(file);
      },
      upload(){
        this.$Progress.start()
        this.form.post('api/upload')
          .then((response) => {
            Fire.$emit('AfterUpdateImagesLoad'); //custom events
            Toast.fire({
              icon: 'success',
              title: 'Images uploaded successfully'
            });
            this.$Progress.finish();
            this.images = response.data.images;
            for (let index = 0; index < this.files.length; index++) {
              this.$refs.myVueDropzone.removeFile(this.files[index]);
            }
          })
          .catch(() => {
            Toast.fire({
              icon: 'Error',
              title: 'Something went wrong'
            });
          });
      },
      loadUsers() {
        axios.get("api/user").then( data => (this.users = data.data));
      },
    },
    created() { 
      //this.loadImages();
      Fire.$on('AfterUpdateImagesLoad',()=>{ //custom events fire on
        //this.loadImages();
      });
    }
  }
</script> 