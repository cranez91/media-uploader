<template>
  <div>
    <v-toolbar color="deep-purple lighten-2" class="white--text" dense>
      <v-toolbar-title>
        Media Uploader
      </v-toolbar-title>
    </v-toolbar>
    <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" @vdropzone-thumbnail="vfileAdded"></vue-dropzone>
    
    <v-row no-gutters>
      <v-col>
        <v-btn v-if="files.length > 0" block color="red lighten-3" @click="removeAll">Clear</v-btn>
      </v-col>
      <v-col>
        <v-btn v-if="files.length > 0" block color="green lighten-2" @click="upload">Upload</v-btn>
      </v-col>
    </v-row>

    <v-toolbar v-if="images.length > 0" class="white--text" color="deep-purple lighten-2" dense>
      <v-toolbar-title>
        Uploaded Images
      </v-toolbar-title>
    </v-toolbar>

    <v-container fluid>
      <v-row justify="space-around">
        <v-col cols="10">
          <v-row>
            <v-col lg="2" md="4" sm="6" xs="12" v-for="(image, index) in images" :key="index">
              <v-img :src="image" aspect-ratio="1" width="100"></v-img>
            </v-col>
          </v-row>
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
      loadImages(){
        this.images = window.store.getters.images;
      },
      removeAll(){
        for (let index = 0; index < this.files.length; index++) {
          this.$refs.myVueDropzone.removeFile(this.files[index]);
        }
        this.files = [];
      },
      vfileAdded(file) {
        if (file.type != "image/png") {
          this.$refs.myVueDropzone.removeFile(file);
          Swal.fire({
            icon: 'error',
            title: 'Sorry',
            text: 'Only PNG files are allowed',
            allowOutsideClick: false
          });
          return;
        }
        this.form.images.push(file.dataURL);
        this.files.push(file);
      },
      upload(){
        this.$Progress.start()
        this.form.post('api/upload')
          .then((response) => {
            Swal.fire({
              icon: 'success',
              title: 'Awesome!',
              text: 'Image(s) uploaded successfully',
              allowOutsideClick: false
            });
            this.$Progress.finish();
            window.store.commit("addImages", response.data.images);
            Fire.$emit('AfterUpdateImagesLoad'); //custom events
            for (let index = 0; index < this.files.length; index++) {
              this.$refs.myVueDropzone.removeFile(this.files[index]);
            }
          })
          .catch((error) => {
            if (error.response) {
              var errors = error.response.data.errors;
              var msg = "";
              for(error in errors){
                msg += errors[error][0] + "\n";
              }
              var msg = 
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: msg
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Something went wrong, please try again'
              });
            }
          });
      }
    },
    created() { 
      this.loadImages();
      Fire.$on('AfterUpdateImagesLoad',()=>{ //custom events fire on
        this.loadImages();
      });
    }
  }
</script> 