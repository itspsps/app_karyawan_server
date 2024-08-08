@extends('layouts.dashboard')
@section('isi')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white"
                style="background: linear-gradient(141.89deg,rgb(254, 153, 0) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">
                <span class="
                  img-circle
                  d-flex
                  align-items-center
                  justify-content-center
                  bg-white
                " style="
                  width: 70px;
                  height: 70px;
                  line-height: 70px;
                  font-size: 35px;
                ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#FE9900">
                        <path
                            d="M29.3359 21.4062C24.2096 21.4062 20.0391 25.5768 20.0391 30.7031C20.0391 35.8295 24.2096 40 29.3359 40C34.5053 40 38.7109 35.8295 38.7109 30.7031C38.7109 25.5768 34.5053 21.4062 29.3359 21.4062ZM32.1893 29.5784L29.0643 33.3284C28.8402 33.5972 28.5095 33.75 28.1641 33.75C28.1192 33.75 28.0741 33.7474 28.0289 33.7422C27.6361 33.6966 27.2927 33.4559 27.1159 33.1022L25.8659 30.6022C25.5764 30.0234 25.8111 29.3194 26.3899 29.03C26.9687 28.7404 27.6727 28.9752 27.9622 29.5541L28.4109 30.4514L30.3888 28.0779C30.8031 27.5808 31.5421 27.5137 32.0392 27.9278C32.5365 28.3422 32.6037 29.0811 32.1893 29.5784Z"
                            fill="#FE9900" />
                        <path
                            d="M28.8309 6.67602L24.076 1.75891C24.0737 1.75656 24.0714 1.75414 24.0691 1.7518C22.9744 0.638516 21.451 0 19.8898 0H4.80469C2.86617 0 1.28906 1.57711 1.28906 3.51562V33.9062C1.28906 35.8448 2.86617 37.4219 4.80469 37.4219H19.8352C18.488 35.5225 17.6953 33.2038 17.6953 30.7031C17.6953 24.2845 22.9173 19.0625 29.3359 19.0625C29.7314 19.0625 30.1223 19.0823 30.5078 19.1205V10.7797C30.5078 9.23523 29.9123 7.77813 28.8309 6.67602ZM13.7109 26.1719H7.46094C6.81375 26.1719 6.28906 25.6472 6.28906 25C6.28906 24.3528 6.81375 23.8281 7.46094 23.8281H13.7109C14.3581 23.8281 14.8828 24.3528 14.8828 25C14.8828 25.6472 14.3581 26.1719 13.7109 26.1719ZM13.7109 19.9219H7.46094C6.81375 19.9219 6.28906 19.3972 6.28906 18.75C6.28906 18.1028 6.81375 17.5781 7.46094 17.5781H13.7109C14.3581 17.5781 14.8828 18.1028 14.8828 18.75C14.8828 19.3972 14.3581 19.9219 13.7109 19.9219ZM13.7109 13.6719H7.46094C6.81375 13.6719 6.28906 13.1472 6.28906 12.5C6.28906 11.8528 6.81375 11.3281 7.46094 11.3281H13.7109C14.3581 11.3281 14.8828 11.8528 14.8828 12.5C14.8828 13.1472 14.3581 13.6719 13.7109 13.6719ZM21.2109 19.9219H19.9609C19.3137 19.9219 18.7891 19.3972 18.7891 18.75C18.7891 18.1028 19.3137 17.5781 19.9609 17.5781H21.2109C21.8581 17.5781 22.3828 18.1028 22.3828 18.75C22.3828 19.3972 21.8581 19.9219 21.2109 19.9219ZM21.2109 13.6719H19.9609C19.3137 13.6719 18.7891 13.1472 18.7891 12.5C18.7891 11.8528 19.3137 11.3281 19.9609 11.3281H21.2109C21.8581 11.3281 22.3828 11.8528 22.3828 12.5C22.3828 13.1472 21.8581 13.6719 21.2109 13.6719Z"
                            fill="#FE9900" />
                    </svg>
                </span>
                <!-- <span class="info-box-icon bg-info elevation-1">
                <i class="fa fa-users"></i>
              </span>-->
                <div class="info-box-content">
                    <!-- <span class="info-box-text text-info">Total User</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__bounce"
                        style="color: rgb(254, 153, 0)">
                        Total Karyawan
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">
                        <small class="badge badge-warning font-size-16">
                            {{ $jumlah_user }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white mb-3"
                style="background: linear-gradient(141.89deg,rgb(71, 188, 188) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">
                <span class="
                img-circle
                d-flex
                align-items-center
                justify-content-center
                bg-white
              " style="
                width: 70px;
                height: 70px;
                line-height: 70px;
                font-size: 35px;
              ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#47BCBC">
                        <path
                            d="M28 0H6C5 0 4 1 4 2V38C4 39.2 5 40 6 40H34C35 40 36 39.2 36 38V8L28 0ZM14 10H16V8C16 7 17 6 18 6C19 6 20 7 20 8V10H22C23 10 24 11 24 12C24 13.2 23 14 22 14H20V16C20 17.2 19 18 18 18C17 18 16 17.2 16 16V14H14C13 14 12 13.2 12 12C12 11 13 10 14 10ZM22 34H10C9 34 8 33 8 32C8 31 9 30 10 30H22C23.2 30 24 31 24 32C24 33 23 34 22 34ZM30 26H10C9 26 8 25 8 24C8 23 9 22 10 22H30C31.2 22 32 23 32 24C32 25 31 26 30 26Z"
                            fill="#47BCBC" />
                    </svg>
                </span>
                <!-- <span class="info-box-icon bg-danger elevation-1">
              <i class="fa fa-user"></i>
            </span>-->

                <div class="info-box-content">
                    <!-- <span class="info-box-text text-danger">Total User Pendamping</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__bounce"
                        style="color: rgb(71, 188, 188)">
                        Masuk
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">
                        <small class="badge badge-info font-size-16" v-for="(item, index) in totaluser">
                            {{ $jumlah_masuk + $jumlah_izin_telat + $jumlah_izin_pulang_cepat  }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white mb-3"
                style="background: linear-gradient(141.89deg,rgb(241, 90, 90) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">
                <!-- <span class="info-box-icon bg-success elevation-1">
              <i class="fa fa-user-times"></i>
            </span>-->
                <span class="
                img-circle
                d-flex
                align-items-center
                justify-content-center
                bg-white
              " style="
                width: 70px;
                height: 70px;
                line-height: 70px;
                font-size: 35px;
              ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#F15A5A">
                        <path
                            d="M36.7998 0C35.1998 0 33.7998 1.4 33.7998 3V6H39.7998V3C39.7998 1.4 38.5998 0 36.7998 0Z"
                            fill="#F15A5A" />
                        <path d="M34 10V29C34 30.6 35.4 40 37 40C38.6 40 40 30.6 40 29V10H34Z" fill="#F15A5A" />
                        <path
                            d="M22 0H2C0.8 0 0 0.8 0 2V38C0 39.2 1 40 2 40H28C29.2 40 30 39.2 30 38V8L22 0ZM6 6H18C19.2 6 20 6.8 20 8C20 9.2 19.2 10 18 10H6C4.8 10 4 9.2 4 8C4 6.8 4.8 6 6 6ZM25.4 27.4L17.4 35.4C16.6 36.2 15 36.2 14.4 35L11.8 31L7.6 35.2C6.8 36 5.6 36 4.8 35.2C4 34.4 4 33.2 4.8 32.4L10.8 26.4C11.6 25.6 13.2 25.6 13.8 26.8L16.4 30.8L22.6 24.6C23.4 23.8 24.6 23.8 25.4 24.6C26.2 25.4 26.2 26.6 25.4 27.4ZM24 18H6C4.8 18 4 17.2 4 16C4 14.8 4.8 14 6 14H24C25.2 14 26 14.8 26 16C26 17.2 25.2 18 24 18Z"
                            fill="#F15A5A" />
                    </svg>
                </span>

                <div class="info-box-content">
                    <!-- <span class="info-box-text text-success">Total User Apd Kab/Kota</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__fadeInRightBig" style="color: rgb(241, 90, 90)">
                        Alfa
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">
                        <small class="badge badge-danger font-size-16" v-for="(item, index) in totaluser">
                        {{ $jumlah_tidak_masuk }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white mb-3"
                style="background: linear-gradient(141.89deg,rgb(119, 119, 219) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">
                <span class="
                img-circle
                d-flex
                align-items-center
                justify-content-center
                bg-white
              " style="
                width: 70px;
                height: 70px;
                line-height: 70px;
                font-size: 35px;
              ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#7777DB">
                        <path
                            d="M24.6875 2.34375H23.3154C22.8313 0.979609 21.5278 0 20 0C18.4722 0 17.1688 0.979609 16.6846 2.34375H15.3125C13.3739 2.34375 11.7969 3.92078 11.7969 5.85938C11.7969 6.50711 12.321 7.03125 12.9688 7.03125H27.0312C27.679 7.03125 28.2031 6.50711 28.2031 5.85938C28.2031 3.92078 26.6261 2.34375 24.6875 2.34375Z"
                            fill="#7777DB" />
                        <path
                            d="M31.7188 4.6875H30.4283C30.5055 5.06633 30.5469 5.45812 30.5469 5.85938C30.5469 7.79797 28.9698 9.375 27.0312 9.375H12.9688C11.0302 9.375 9.45312 7.79797 9.45312 5.85938C9.45312 5.45812 9.49445 5.06633 9.57172 4.6875H8.28125C6.34266 4.6875 4.76562 6.26453 4.76562 8.20312V36.4844C4.76562 38.423 6.34266 40 8.28125 40H31.7188C33.6573 40 35.2344 38.423 35.2344 36.4844V8.20312C35.2344 6.26453 33.6573 4.6875 31.7188 4.6875ZM16.4844 32.8125H11.7969C11.1491 32.8125 10.625 32.2884 10.625 31.6406C10.625 30.9929 11.1491 30.4688 11.7969 30.4688H16.4844C17.1321 30.4688 17.6562 30.9929 17.6562 31.6406C17.6562 32.2884 17.1321 32.8125 16.4844 32.8125ZM16.4844 24.6094H11.7969C11.1491 24.6094 10.625 24.0852 10.625 23.4375C10.625 22.7898 11.1491 22.2656 11.7969 22.2656H16.4844C17.1321 22.2656 17.6562 22.7898 17.6562 23.4375C17.6562 24.0852 17.1321 24.6094 16.4844 24.6094ZM16.4844 16.4062H11.7969C11.1491 16.4062 10.625 15.8821 10.625 15.2344C10.625 14.5866 11.1491 14.0625 11.7969 14.0625H16.4844C17.1321 14.0625 17.6562 14.5866 17.6562 15.2344C17.6562 15.8821 17.1321 16.4062 16.4844 16.4062ZM27.8598 33.1559C28.3175 33.6136 28.3175 34.3552 27.8598 34.813C27.6309 35.0419 27.331 35.1563 27.0312 35.1563C26.7315 35.1563 26.4316 35.0419 26.2027 34.813L24.6875 33.2977L23.1723 34.813C22.9434 35.0419 22.6435 35.1563 22.3438 35.1563C22.044 35.1563 21.7441 35.0419 21.5152 34.813C21.0575 34.3552 21.0575 33.6136 21.5152 33.1559L23.0304 31.6406L21.5152 30.1254C21.0574 29.6677 21.0574 28.926 21.5152 28.4683C21.9729 28.0105 22.7145 28.0105 23.1723 28.4683L24.6875 29.9835L26.2027 28.4683C26.6605 28.0105 27.4021 28.0105 27.8598 28.4683C28.3176 28.926 28.3176 29.6677 27.8598 30.1254L26.3446 31.6406L27.8598 33.1559ZM29.0316 21.9223L25.516 25.4379C25.2871 25.6668 24.9873 25.7812 24.6875 25.7812C24.3877 25.7812 24.0878 25.6668 23.859 25.4379L21.5152 23.0941C21.0575 22.6364 21.0575 21.8948 21.5152 21.437C21.973 20.9793 22.7146 20.9793 23.1723 21.437L24.6875 22.9523L27.3746 20.2652C27.8323 19.8074 28.574 19.8074 29.0317 20.2652C29.4895 20.7229 29.4895 21.4645 29.0316 21.9223ZM29.0316 14.891L25.516 18.4066C25.2871 18.6355 24.9873 18.75 24.6875 18.75C24.3877 18.75 24.0878 18.6355 23.859 18.4066L21.5152 16.0629C21.0575 15.6052 21.0575 14.8635 21.5152 14.4058C21.973 13.948 22.7146 13.948 23.1723 14.4058L24.6875 15.921L27.3746 13.2339C27.8323 12.7762 28.574 12.7762 29.0317 13.2339C29.4895 13.6916 29.4895 14.4333 29.0316 14.891Z"
                            fill="#7777DB" />
                    </svg>
                </span>
                <!-- <span class="info-box-icon bg-warning elevation-1">
              <i class="fa fa-user-plus"></i>
            </span>-->

                <div class="info-box-content">
                    <!-- <span class="info-box-text text-warning">Total User Korkab</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__fadeInRightBig" style="color: rgb(119, 119, 219)">
                        Libur
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">
                        <small class="badge bg-purple font-size-16" v-for="(item, index) in totaluser">
                          {{ $jumlah_libur }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white"
                style="background: linear-gradient(141.89deg,rgb(71, 188, 188) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">
                <span class="
                  img-circle
                  d-flex
                  align-items-center
                  justify-content-center
                  bg-white
                " style="
                  width: 70px;
                  height: 70px;
                  line-height: 70px;
                  font-size: 35px;
                ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#47BCBC">
                        <path
                            d="M29.3359 21.4062C24.2096 21.4062 20.0391 25.5768 20.0391 30.7031C20.0391 35.8295 24.2096 40 29.3359 40C34.5053 40 38.7109 35.8295 38.7109 30.7031C38.7109 25.5768 34.5053 21.4062 29.3359 21.4062ZM32.1893 29.5784L29.0643 33.3284C28.8402 33.5972 28.5095 33.75 28.1641 33.75C28.1192 33.75 28.0741 33.7474 28.0289 33.7422C27.6361 33.6966 27.2927 33.4559 27.1159 33.1022L25.8659 30.6022C25.5764 30.0234 25.8111 29.3194 26.3899 29.03C26.9687 28.7404 27.6727 28.9752 27.9622 29.5541L28.4109 30.4514L30.3888 28.0779C30.8031 27.5808 31.5421 27.5137 32.0392 27.9278C32.5365 28.3422 32.6037 29.0811 32.1893 29.5784Z"
                            fill="#47BCB" />
                        <path
                            d="M28.8309 6.67602L24.076 1.75891C24.0737 1.75656 24.0714 1.75414 24.0691 1.7518C22.9744 0.638516 21.451 0 19.8898 0H4.80469C2.86617 0 1.28906 1.57711 1.28906 3.51562V33.9062C1.28906 35.8448 2.86617 37.4219 4.80469 37.4219H19.8352C18.488 35.5225 17.6953 33.2038 17.6953 30.7031C17.6953 24.2845 22.9173 19.0625 29.3359 19.0625C29.7314 19.0625 30.1223 19.0823 30.5078 19.1205V10.7797C30.5078 9.23523 29.9123 7.77813 28.8309 6.67602ZM13.7109 26.1719H7.46094C6.81375 26.1719 6.28906 25.6472 6.28906 25C6.28906 24.3528 6.81375 23.8281 7.46094 23.8281H13.7109C14.3581 23.8281 14.8828 24.3528 14.8828 25C14.8828 25.6472 14.3581 26.1719 13.7109 26.1719ZM13.7109 19.9219H7.46094C6.81375 19.9219 6.28906 19.3972 6.28906 18.75C6.28906 18.1028 6.81375 17.5781 7.46094 17.5781H13.7109C14.3581 17.5781 14.8828 18.1028 14.8828 18.75C14.8828 19.3972 14.3581 19.9219 13.7109 19.9219ZM13.7109 13.6719H7.46094C6.81375 13.6719 6.28906 13.1472 6.28906 12.5C6.28906 11.8528 6.81375 11.3281 7.46094 11.3281H13.7109C14.3581 11.3281 14.8828 11.8528 14.8828 12.5C14.8828 13.1472 14.3581 13.6719 13.7109 13.6719ZM21.2109 19.9219H19.9609C19.3137 19.9219 18.7891 19.3972 18.7891 18.75C18.7891 18.1028 19.3137 17.5781 19.9609 17.5781H21.2109C21.8581 17.5781 22.3828 18.1028 22.3828 18.75C22.3828 19.3972 21.8581 19.9219 21.2109 19.9219ZM21.2109 13.6719H19.9609C19.3137 13.6719 18.7891 13.1472 18.7891 12.5C18.7891 11.8528 19.3137 11.3281 19.9609 11.3281H21.2109C21.8581 11.3281 22.3828 11.8528 22.3828 12.5C22.3828 13.1472 21.8581 13.6719 21.2109 13.6719Z"
                            fill="#47BCBC" />
                    </svg>
                </span>
                <!-- <span class="info-box-icon bg-info elevation-1">
                <i class="fa fa-users"></i>
              </span>-->
                <div class="info-box-content">
                    <!-- <span class="info-box-text text-info">Total User</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__bounce"
                        style="color: rgb(71, 188, 188);">
                        Lembur
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">
                        <small class="badge badge-info font-size-16">
                        {{ $jumlah_karyawan_lembur }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white mb-3"
            style="background: linear-gradient(141.89deg,rgb(241, 90, 90) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">
                <span class="
                img-circle
                d-flex
                align-items-center
                justify-content-center
                bg-white
              " style="
                width: 70px;
                height: 70px;
                line-height: 70px;
                font-size: 35px;
              ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#F15A5A">
                        <path
                            d="M28 0H6C5 0 4 1 4 2V38C4 39.2 5 40 6 40H34C35 40 36 39.2 36 38V8L28 0ZM14 10H16V8C16 7 17 6 18 6C19 6 20 7 20 8V10H22C23 10 24 11 24 12C24 13.2 23 14 22 14H20V16C20 17.2 19 18 18 18C17 18 16 17.2 16 16V14H14C13 14 12 13.2 12 12C12 11 13 10 14 10ZM22 34H10C9 34 8 33 8 32C8 31 9 30 10 30H22C23.2 30 24 31 24 32C24 33 23 34 22 34ZM30 26H10C9 26 8 25 8 24C8 23 9 22 10 22H30C31.2 22 32 23 32 24C32 25 31 26 30 26Z"
                            fill="#F15A5A" />
                    </svg>
                </span>
                <!-- <span class="info-box-icon bg-danger elevation-1">
              <i class="fa fa-user"></i>
            </span>-->

                <div class="info-box-content">
                    <!-- <span class="info-box-text text-danger">Total User Pendamping</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__bounce"
                        style="color: rgb(241, 90, 90)">                        
                        Izin Telat
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">                        
                        <small class="badge badge-danger font-size-16">
                        {{ $jumlah_izin_telat }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="clearfix hidden-md-up"></div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white mb-3"
                style="background: linear-gradient(141.89deg,rgb(119, 119, 219) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">                
                <!-- <span class="info-box-icon bg-success elevation-1">
              <i class="fa fa-user-times"></i>
            </span>-->
                <span class="
                img-circle
                d-flex
                align-items-center
                justify-content-center
                bg-white
              " style="
                width: 70px;
                height: 70px;
                line-height: 70px;
                font-size: 35px;
              ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#7777DB">
                        <path
                            d="M36.7998 0C35.1998 0 33.7998 1.4 33.7998 3V6H39.7998V3C39.7998 1.4 38.5998 0 36.7998 0Z"
                            fill="#7777DB" />
                        <path d="M34 10V29C34 30.6 35.4 40 37 40C38.6 40 40 30.6 40 29V10H34Z" fill="#7777DB" />
                        <path
                            d="M22 0H2C0.8 0 0 0.8 0 2V38C0 39.2 1 40 2 40H28C29.2 40 30 39.2 30 38V8L22 0ZM6 6H18C19.2 6 20 6.8 20 8C20 9.2 19.2 10 18 10H6C4.8 10 4 9.2 4 8C4 6.8 4.8 6 6 6ZM25.4 27.4L17.4 35.4C16.6 36.2 15 36.2 14.4 35L11.8 31L7.6 35.2C6.8 36 5.6 36 4.8 35.2C4 34.4 4 33.2 4.8 32.4L10.8 26.4C11.6 25.6 13.2 25.6 13.8 26.8L16.4 30.8L22.6 24.6C23.4 23.8 24.6 23.8 25.4 24.6C26.2 25.4 26.2 26.6 25.4 27.4ZM24 18H6C4.8 18 4 17.2 4 16C4 14.8 4.8 14 6 14H24C25.2 14 26 14.8 26 16C26 17.2 25.2 18 24 18Z"
                            fill="#7777DB" />
                    </svg>
                </span>

                <div class="info-box-content">
                    <!-- <span class="info-box-text text-success">Total User Apd Kab/Kota</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__fadeInRightBig" 
                    style="color: rgb(119, 119, 219)">
                        Cuti
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">
                        <small class="badge bg-purple font-size-16">                        
                        {{ $jumlah_cuti }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-white mb-3"
                style="background: linear-gradient(141.89deg,rgb(254, 153, 0) -70%,rgba(254, 153, 0, 0) 50%)no-repeat;">                
                <span class="
                img-circle
                d-flex
                align-items-center
                justify-content-center
                bg-white
              " style="
                width: 70px;
                height: 70px;
                line-height: 70px;
                font-size: 35px;
              ">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="#FE9900">
                        <path
                            d="M24.6875 2.34375H23.3154C22.8313 0.979609 21.5278 0 20 0C18.4722 0 17.1688 0.979609 16.6846 2.34375H15.3125C13.3739 2.34375 11.7969 3.92078 11.7969 5.85938C11.7969 6.50711 12.321 7.03125 12.9688 7.03125H27.0312C27.679 7.03125 28.2031 6.50711 28.2031 5.85938C28.2031 3.92078 26.6261 2.34375 24.6875 2.34375Z"
                            fill="#FE9900" />
                        <path
                            d="M31.7188 4.6875H30.4283C30.5055 5.06633 30.5469 5.45812 30.5469 5.85938C30.5469 7.79797 28.9698 9.375 27.0312 9.375H12.9688C11.0302 9.375 9.45312 7.79797 9.45312 5.85938C9.45312 5.45812 9.49445 5.06633 9.57172 4.6875H8.28125C6.34266 4.6875 4.76562 6.26453 4.76562 8.20312V36.4844C4.76562 38.423 6.34266 40 8.28125 40H31.7188C33.6573 40 35.2344 38.423 35.2344 36.4844V8.20312C35.2344 6.26453 33.6573 4.6875 31.7188 4.6875ZM16.4844 32.8125H11.7969C11.1491 32.8125 10.625 32.2884 10.625 31.6406C10.625 30.9929 11.1491 30.4688 11.7969 30.4688H16.4844C17.1321 30.4688 17.6562 30.9929 17.6562 31.6406C17.6562 32.2884 17.1321 32.8125 16.4844 32.8125ZM16.4844 24.6094H11.7969C11.1491 24.6094 10.625 24.0852 10.625 23.4375C10.625 22.7898 11.1491 22.2656 11.7969 22.2656H16.4844C17.1321 22.2656 17.6562 22.7898 17.6562 23.4375C17.6562 24.0852 17.1321 24.6094 16.4844 24.6094ZM16.4844 16.4062H11.7969C11.1491 16.4062 10.625 15.8821 10.625 15.2344C10.625 14.5866 11.1491 14.0625 11.7969 14.0625H16.4844C17.1321 14.0625 17.6562 14.5866 17.6562 15.2344C17.6562 15.8821 17.1321 16.4062 16.4844 16.4062ZM27.8598 33.1559C28.3175 33.6136 28.3175 34.3552 27.8598 34.813C27.6309 35.0419 27.331 35.1563 27.0312 35.1563C26.7315 35.1563 26.4316 35.0419 26.2027 34.813L24.6875 33.2977L23.1723 34.813C22.9434 35.0419 22.6435 35.1563 22.3438 35.1563C22.044 35.1563 21.7441 35.0419 21.5152 34.813C21.0575 34.3552 21.0575 33.6136 21.5152 33.1559L23.0304 31.6406L21.5152 30.1254C21.0574 29.6677 21.0574 28.926 21.5152 28.4683C21.9729 28.0105 22.7145 28.0105 23.1723 28.4683L24.6875 29.9835L26.2027 28.4683C26.6605 28.0105 27.4021 28.0105 27.8598 28.4683C28.3176 28.926 28.3176 29.6677 27.8598 30.1254L26.3446 31.6406L27.8598 33.1559ZM29.0316 21.9223L25.516 25.4379C25.2871 25.6668 24.9873 25.7812 24.6875 25.7812C24.3877 25.7812 24.0878 25.6668 23.859 25.4379L21.5152 23.0941C21.0575 22.6364 21.0575 21.8948 21.5152 21.437C21.973 20.9793 22.7146 20.9793 23.1723 21.437L24.6875 22.9523L27.3746 20.2652C27.8323 19.8074 28.574 19.8074 29.0317 20.2652C29.4895 20.7229 29.4895 21.4645 29.0316 21.9223ZM29.0316 14.891L25.516 18.4066C25.2871 18.6355 24.9873 18.75 24.6875 18.75C24.3877 18.75 24.0878 18.6355 23.859 18.4066L21.5152 16.0629C21.0575 15.6052 21.0575 14.8635 21.5152 14.4058C21.973 13.948 22.7146 13.948 23.1723 14.4058L24.6875 15.921L27.3746 13.2339C27.8323 12.7762 28.574 12.7762 29.0317 13.2339C29.4895 13.6916 29.4895 14.4333 29.0316 14.891Z"
                            fill="#FE9900" />
                    </svg>
                </span>
                <!-- <span class="info-box-icon bg-warning elevation-1">
              <i class="fa fa-user-plus"></i>
            </span>-->

                <div class="info-box-content">
                    <!-- <span class="info-box-text text-warning">Total User Korkab</span> -->
                    <span class="font-size-24 font-weight-bold animate__animated animate__fadeInRightBig" 
                        style="color: rgb(254, 153, 0)">
                        Izin Pulang
                    </span>
                    <span class="info-box-number animate__animated animate__fadeInRightBig">
                        <small class="badge badge-warning font-size-16">                        
                        {{ $jumlah_izin_pulang_cepat }}
                        </small>
                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
</div>

<!-- Activity Logs -->
<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h4 class="card-title">History Aktivitas Terbaru</h4>
        </div>
        <div class="card-body p-0">
            <!-- THE CALENDAR -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Description</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                        <tr>
                            <td>{{ 1 + $loop->index }}</td>
                            <td>{{ $log->user->name }}</td>
                            <td>{{ ucfirst($log->activity) }}</td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.col -->

<div class="row">
    <div class="col-md-3">
        <div class="sticky-top mb-3">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h4 class="card-title">Draggable Events</h4>
                </div>
                <div class="card-body">
                    <!-- the events -->
                    <div id="external-events">
                        <div class="external-event bg-success">Lunch</div>
                        <div class="external-event bg-warning">Go home</div>
                        <div class="external-event bg-info">Do homework</div>
                        <div class="external-event bg-primary">Work on UI design</div>
                        <div class="external-event bg-danger">Sleep tight</div>
                        <div class="checkbox">
                            <label for="drop-remove">
                                <input type="checkbox" id="drop-remove">
                                remove after drop
                            </label>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create Event</h3>
                </div>
                <div class="card-body">
                    <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                        <ul class="fc-color-picker" id="color-chooser">
                            <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                            <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                            <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                            <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                            <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                        </ul>
                    </div>
                    <!-- /btn-group -->
                    <div class="input-group">
                        <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                        <div class="input-group-append">
                            <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                        </div>
                        <!-- /btn-group -->
                    </div>
                    <!-- /input-group -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="card card-outline card-primary">
            <div class="card-body p-0">
                <!-- THE CALENDAR -->
                <div id="calendar"></div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>

</div>
<br>
@endsection