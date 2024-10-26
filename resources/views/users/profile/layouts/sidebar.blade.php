	<!-- Sidebar -->
	<div class="sidebar">
		<div class="author-box">
			<div class="dz-media">
				@if(Auth::user()->foto_karyawan == '' || Auth::user()->foto_karyawan == NULL)
				<img src="{{asset('admin/assets/img/avatars/1.png')}}" class="rounded-circle" alt="author-image">
				@else
				<img src="{{ url('https://hrd.sumberpangan.store:4430/storage/app/public/foto_karyawan/'.Auth::user()->foto_karyawan) }}" class="rounded-circle" alt="author-image">
				@endif
			</div>
			<div class="dz-info">
				<span>
					<?php
					date_default_timezone_set("Asia/Jakarta");
					$time = date("H");
					/* Set the $timezone variable to become the current timezone */
					$timezone = date("e");
					/* If the time is less than 1200 hours, show good morning */
					if ($time < "12") {
						echo "Good morning";
					} else
						/* If the time is grater than or equal to 1200 hours, but less than 1700 hours, so good afternoon */
						if ($time >= "12" && $time < "17") {
							echo "Good afternoon";
						} else
							/* Should the time be between or equal to 1700 and 1900 hours, show good evening */
							if ($time >= "17" && $time < "19") {
								echo "Good evening";
							} else
								/* Finally, show good night if the time is greater than or equal to 1900 hours */
								if ($time >= "19") {
									echo "Good night";
								}
					?>
				</span>
				<h5 class="name">{{ Auth::user()->name }}</h5>
			</div>
		</div>
		<ul class="nav navbar-nav">
			<li class="nav-label">Main Menu</li>
			<li><a class="nav-link" id="btn_klik" href="{{ url('/home/absen') }}">
					<span class="dz-icon bg-red light">
						<svg xmlns="http://www.w3.org/2000/svg" class="ionicon s-ion-icon" viewBox="0 0 512 512">
							<title>Scan</title>
							<path d="M336 448h56a56 56 0 0056-56v-56M448 176v-56a56 56 0 00-56-56h-56M176 448h-56a56 56 0 01-56-56v-56M64 176v-56a56 56 0 0156-56h56" stroke-linecap="round" stroke-linejoin="round" class="ionicon-fill-none ionicon-stroke-width"></path>
						</svg>
					</span>
					<span>Absen</span>
				</a></li>
			<li>
				<a class="nav-link" id="btn_klik" href="{{ url('/izin/dashboard/') }}">
					<span class="dz-icon bg-pink light">
						<svg xmlns="http://www.w3.org/2000/svg" class="ionicon s-ion-icon" viewBox="0 0 512 512">
							<title>Documents</title>
							<path d="M336 264.13V436c0 24.3-19.05 44-42.95 44H107c-23.95 0-43-19.7-43-44V172a44.26 44.26 0 0144-44h94.12a24.55 24.55 0 0117.49 7.36l109.15 111a25.4 25.4 0 017.24 17.77z" stroke-linejoin="round" class="ionicon-fill-none ionicon-stroke-width"></path>
							<path d="M200 128v108a28.34 28.34 0 0028 28h108" stroke-linecap="round" stroke-linejoin="round" class="ionicon-fill-none ionicon-stroke-width"></path>
							<path d="M176 128V76a44.26 44.26 0 0144-44h94a24.83 24.83 0 0117.61 7.36l109.15 111A25.09 25.09 0 01448 168v172c0 24.3-19.05 44-42.95 44H344" stroke-linejoin="round" class="ionicon-fill-none ionicon-stroke-width"></path>
							<path d="M312 32v108a28.34 28.34 0 0028 28h108" stroke-linecap="round" stroke-linejoin="round" class="ionicon-fill-none ionicon-stroke-width"></path>
						</svg>
					</span>
					<span>Izin</span>
				</a>
			</li>
			<li>
				<a class="nav-link" id="btn_klik" href="{{ url('/cuti/dashboard/') }}">
					<span class="dz-icon bg-orange light">
						<svg fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 511.997 511.997" xml:space="preserve">
							<g>
								<g>
									<g>
										<polygon points="298.665,409.595 298.665,357.73 270.377,409.595 			" />
										<path d="M383.999,119.46h59.733v-8.533c0-14.114-11.486-25.6-25.6-25.6h-64.964L261.741,2.221c-3.251-2.961-8.235-2.961-11.486,0
                                            l-91.426,83.106H93.865c-14.114,0-25.6,11.486-25.6,25.6v8.533h59.733c4.719,0,8.533,3.823,8.533,8.533
                                            c0,4.719-3.814,8.533-8.533,8.533H68.265v17.067h375.467v-17.067h-59.733c-4.719,0-8.533-3.814-8.533-8.533
                                            C375.465,123.283,379.28,119.46,383.999,119.46z M184.208,85.327l71.791-65.263l71.791,65.263H184.208z" />
										<path d="M324.265,221.864c0-4.702-3.823-8.533-8.533-8.533h-8.533v17.067h8.533C320.442,230.397,324.265,226.566,324.265,221.864
                                            z" />
										<path d="M68.265,486.397c0,14.114,11.486,25.6,25.6,25.6h324.267c14.114,0,25.6-11.486,25.6-25.6V170.664H68.265V486.397z
                                            M290.132,238.93v-34.133c0-4.71,3.814-8.533,8.533-8.533h17.067c14.114,0,25.6,11.486,25.6,25.6
                                            c0,6.579-2.569,12.527-6.656,17.067c4.088,4.54,6.656,10.487,6.656,17.067c0,14.114-11.486,25.6-25.6,25.6h-17.067
                                            c-4.719,0-8.533-3.823-8.533-8.533V238.93z M294.569,329.597c1.638-3.012,3.166-6.144,4.949-9.079
                                            c2.099-3.43,6.153-5.538,10.163-4.309c7.39,2.27,6.05,11.23,6.05,17.28v22.528v26.411v20.847v6.323h8.533
                                            c4.719,0,8.533,3.823,8.533,8.533s-3.814,8.533-8.533,8.533h-8.533v34.133c0,4.71-3.814,8.533-8.533,8.533
                                            s-8.533-3.823-8.533-8.533v-34.133h-14.541h-23.031c-4.608,0-9.591,0.469-12.356-4.241c-3.055-5.222,0.845-10.351,3.311-14.882
                                            c2.987-5.453,5.956-10.914,8.934-16.367c3.925-7.194,7.842-14.379,11.767-21.572c4.019-7.356,8.021-14.72,12.041-22.076
                                            C288.05,341.544,291.309,335.57,294.569,329.597z M230.399,204.797c0-4.71,3.814-8.533,8.533-8.533h25.6
                                            c4.719,0,8.533,3.823,8.533,8.533c0,4.71-3.814,8.533-8.533,8.533h-17.067v17.067h17.067c4.719,0,8.533,3.823,8.533,8.533
                                            s-3.814,8.533-8.533,8.533h-17.067v17.067h17.067c4.719,0,8.533,3.823,8.533,8.533s-3.814,8.533-8.533,8.533h-25.6
                                            c-4.719,0-8.533-3.823-8.533-8.533V204.797z M170.665,204.797c0-4.71,3.814-8.533,8.533-8.533h25.6
                                            c4.719,0,8.533,3.823,8.533,8.533c0,4.71-3.814,8.533-8.533,8.533h-17.067v17.067h17.067c4.719,0,8.533,3.823,8.533,8.533
                                            s-3.814,8.533-8.533,8.533h-17.067v25.6c0,4.71-3.814,8.533-8.533,8.533s-8.533-3.823-8.533-8.533V204.797z M173.165,343.831
                                            l25.591-25.591c0.794-0.794,1.732-1.417,2.782-1.852c2.082-0.862,4.437-0.862,6.519,0c2.091,0.87,3.746,2.534,4.617,4.617
                                            c0.435,1.041,0.657,2.15,0.657,3.26v128h17.067c4.719,0,8.533,3.823,8.533,8.533s-3.814,8.533-8.533,8.533h-51.2
                                            c-4.719,0-8.533-3.823-8.533-8.533s3.814-8.533,8.533-8.533h17.067V344.863l-11.034,11.034c-1.664,1.664-3.849,2.5-6.033,2.5
                                            c-2.185,0-4.369-0.836-6.033-2.5C169.829,352.56,169.829,347.167,173.165,343.831z" />
										<path d="M324.265,255.997c0-4.702-3.823-8.533-8.533-8.533h-8.533v17.067h8.533C320.442,264.53,324.265,260.699,324.265,255.997z
                                            " />
									</g>
								</g>
							</g>
						</svg>
					</span>
					<span>Cuti</span>
				</a>
			</li>
			<li>
				<a class="nav-link" id="btn_klik" href="{{ url('/penugasan/dashboard/') }}">
					<span class="dz-icon bg-orange light">
						<svg fill="#000000" width="800px" height="800px" viewBox="0 0 512 512" id="_x30_1" version="1.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<g>
								<path d="M344.969,211.875H167.031c-10.096,0-18.281,8.185-18.281,18.281s8.185,18.281,18.281,18.281v12.188   c0,10.096,8.185,18.281,18.281,18.281l0,0c10.096,0,18.281-8.185,18.281-18.281v-12.188h104.812v12.188   c0,10.096,8.185,18.281,18.281,18.281l0,0c10.096,0,18.281-8.185,18.281-18.281v-12.188c10.096,0,18.281-8.185,18.281-18.281   S355.065,211.875,344.969,211.875z" />
								<path d="M256,126.562c-20.193,0-36.562,16.37-36.562,36.562h73.125C292.562,142.932,276.193,126.562,256,126.562z" />
								<path d="M256,0C114.615,0,0,114.615,0,256s114.615,256,256,256s256-114.615,256-256S397.385,0,256,0z M412,365.438   C412,385.63,395.63,402,375.438,402H136.562C116.37,402,100,385.63,100,365.438v-165.75c0-20.193,16.37-36.562,36.562-36.562   h46.312C182.875,122.739,215.614,90,256,90l0,0c40.386,0,73.125,32.739,73.125,73.125h46.312c20.193,0,36.562,16.37,36.562,36.562   V365.438z" />
							</g>
						</svg>
					</span>
					<span>Penugasan</span>
				</a>
			</li>
			<!-- <li><a class="nav-link" href="ui-components.html">
					<span class="dz-icon bg-skyblue light">
						<svg width="800px" height="800px" viewBox="0 0 512 512" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
							<title>history-list</title>
							<g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<g id="icon" fill="#000000" transform="translate(33.830111, 42.666667)">
									<path d="M456.836556,405.333333 L456.836556,448 L350.169889,448 L350.169889,405.333333 L456.836556,405.333333 Z M328.836556,405.333333 L328.836556,448 L286.169889,448 L286.169889,405.333333 L328.836556,405.333333 Z M456.836556,341.333333 L456.836556,384 L350.169889,384 L350.169889,341.333333 L456.836556,341.333333 Z M328.836556,341.333333 L328.836556,384 L286.169889,384 L286.169889,341.333333 L328.836556,341.333333 Z M131.660221,261.176335 C154.823665,284.339779 186.823665,298.666667 222.169889,298.666667 C237.130689,298.666667 251.492003,296.099965 264.837506,291.382887 L264.838264,335.956148 C251.200633,339.466383 236.903286,341.333333 222.169889,341.333333 C175.041086,341.333333 132.37401,322.230407 101.489339,291.345231 L131.660221,261.176335 Z M456.836556,277.333333 L456.836556,320 L350.169889,320 L350.169889,277.333333 L456.836556,277.333333 Z M328.836556,277.333333 L328.836556,320 L286.169889,320 L286.169889,277.333333 L328.836556,277.333333 Z M222.169889,7.10542736e-15 C316.426487,7.10542736e-15 392.836556,76.4100694 392.836556,170.666667 C392.836556,201.752854 384.525389,230.897864 370.003903,256.000851 L317.574603,256.00279 C337.844458,233.356846 350.169889,203.451136 350.169889,170.666667 C350.169889,99.9742187 292.862337,42.6666667 222.169889,42.6666667 C151.477441,42.6666667 94.1698893,99.9742187 94.1698893,170.666667 C94.1698893,174.692297 94.3557269,178.674522 94.7192911,182.605232 L115.503223,161.830111 L145.673112,192 L72.836556,264.836556 L4.97379915e-14,192 L30.1698893,161.830111 L51.989071,183.641815 C51.6671112,179.358922 51.5032227,175.031933 51.5032227,170.666667 C51.5032227,76.4100694 127.913292,7.10542736e-15 222.169889,7.10542736e-15 Z M243.503223,64 L243.503223,159.146667 L297.903223,195.626667 L274.436556,231.04 L200.836556,182.186667 L200.836556,64 L243.503223,64 Z" id="Combined-Shape">
									</path>
								</g>
							</g>
						</svg>
					</span>
					<span>History</span>
				</a></li> -->
			<!-- <li>
				<a class="nav-link" href="notification.html">
					<span class="dz-icon bg-green light">
						<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M12.052 1.25H11.948C11.0495 1.24997 10.3003 1.24995 9.70552 1.32991C9.07773 1.41432 8.51093 1.59999 8.05546 2.05546C7.59999 2.51093 7.41432 3.07773 7.32991 3.70552C7.27259 4.13189 7.25637 5.15147 7.25179 6.02566C5.22954 6.09171 4.01536 6.32778 3.17157 7.17157C2 8.34315 2 10.2288 2 14C2 17.7712 2 19.6569 3.17157 20.8284C4.34314 22 6.22876 22 9.99998 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14C22 10.2288 22 8.34315 20.8284 7.17157C19.9846 6.32778 18.7705 6.09171 16.7482 6.02566C16.7436 5.15147 16.7274 4.13189 16.6701 3.70552C16.5857 3.07773 16.4 2.51093 15.9445 2.05546C15.4891 1.59999 14.9223 1.41432 14.2945 1.32991C13.6997 1.24995 12.9505 1.24997 12.052 1.25ZM15.2479 6.00188C15.2434 5.15523 15.229 4.24407 15.1835 3.9054C15.1214 3.44393 15.0142 3.24644 14.8839 3.11612C14.7536 2.9858 14.5561 2.87858 14.0946 2.81654C13.6116 2.7516 12.964 2.75 12 2.75C11.036 2.75 10.3884 2.7516 9.90539 2.81654C9.44393 2.87858 9.24644 2.9858 9.11612 3.11612C8.9858 3.24644 8.87858 3.44393 8.81654 3.9054C8.771 4.24407 8.75661 5.15523 8.75208 6.00188C9.1435 6 9.55885 6 10 6H14C14.4412 6 14.8565 6 15.2479 6.00188ZM12 9.25C12.4142 9.25 12.75 9.58579 12.75 10V10.0102C13.8388 10.2845 14.75 11.143 14.75 12.3333C14.75 12.7475 14.4142 13.0833 14 13.0833C13.5858 13.0833 13.25 12.7475 13.25 12.3333C13.25 11.9493 12.8242 11.4167 12 11.4167C11.1758 11.4167 10.75 11.9493 10.75 12.3333C10.75 12.7174 11.1758 13.25 12 13.25C13.3849 13.25 14.75 14.2098 14.75 15.6667C14.75 16.857 13.8388 17.7155 12.75 17.9898V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V17.9898C10.1612 17.7155 9.25 16.857 9.25 15.6667C9.25 15.2525 9.58579 14.9167 10 14.9167C10.4142 14.9167 10.75 15.2525 10.75 15.6667C10.75 16.0507 11.1758 16.5833 12 16.5833C12.8242 16.5833 13.25 16.0507 13.25 15.6667C13.25 15.2826 12.8242 14.75 12 14.75C10.6151 14.75 9.25 13.7903 9.25 12.3333C9.25 11.143 10.1612 10.2845 11.25 10.0102V10C11.25 9.58579 11.5858 9.25 12 9.25Z" fill="#1C274C" />
						</svg>
					</span>
					<span>Slip Gaji</span>
				</a>
			</li> -->
			<li>
				<a class="nav-link" id="btn_klik" href="{{route('profile')}}">
					<span class="dz-icon bg-yellow light">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#fff" fill-rule="nonzero" opacity="0.3" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#fff" fill-rule="nonzero" />
							</g>
						</svg>
					</span>
					<span>Profile</span>
				</a>
			</li>
			<!-- <li>
				<a class="nav-link" href="messages.html">
					<span class="dz-icon bg-skyblue light">
						<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path opacity="0.5" d="M10 20H13.6266C12.9211 19.1061 12.5 17.9772 12.5 16.75C12.5 13.8505 14.8505 11.5 17.75 11.5C19.4947 11.5 21.0406 12.3511 21.9953 13.6607C22 13.1517 22 12.5997 22 12C22 11.5581 22 10.392 21.9981 10H2.00189C2 10.392 2 11.5581 2 12C2 15.7712 2 17.6569 3.17157 18.8284C4.34315 20 6.22876 20 10 20Z" fill="#1C274C" />
							<path d="M5.25 16C5.25 15.5858 5.58579 15.25 6 15.25H10C10.4142 15.25 10.75 15.5858 10.75 16C10.75 16.4142 10.4142 16.75 10 16.75H6C5.58579 16.75 5.25 16.4142 5.25 16Z" fill="#1C274C" />
							<path fill-rule="evenodd" clip-rule="evenodd" d="M17.75 14.5C16.5074 14.5 15.5 15.5074 15.5 16.75C15.5 17.9926 16.5074 19 17.75 19C18.9926 19 20 17.9926 20 16.75C20 15.5074 18.9926 14.5 17.75 14.5ZM14 16.75C14 14.6789 15.6789 13 17.75 13C19.8211 13 21.5 14.6789 21.5 16.75C21.5 17.5143 21.2713 18.2252 20.8787 18.818L21.7803 19.7197C22.0732 20.0126 22.0732 20.4874 21.7803 20.7803C21.4874 21.0732 21.0126 21.0732 20.7197 20.7803L19.818 19.8787C19.2252 20.2713 18.5143 20.5 17.75 20.5C15.6789 20.5 14 18.8211 14 16.75Z" fill="#1C274C" />
							<path d="M9.99484 4H14.0052C17.7861 4 19.6766 4 20.8512 5.11578C21.6969 5.91916 21.9337 7.07507 22 9V10H2V9C2.0663 7.07507 2.3031 5.91916 3.14881 5.11578C4.3234 4 6.21388 4 9.99484 4Z" fill="#1C274C" />
						</svg>
					</span>
					<span>Id Card</span>
				</a>
			</li> -->
			<li>
				<a class="nav-link" href="onboading.html" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
					<span class="dz-icon bg-red light">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18" height="18" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<rect x="0" y="0" width="24" height="24" />
								<path d="M14.0069431,7.00607258 C13.4546584,7.00607258 13.0069431,6.55855153 13.0069431,6.00650634 C13.0069431,5.45446114 13.4546584,5.00694009 14.0069431,5.00694009 L15.0069431,5.00694009 C17.2160821,5.00694009 19.0069431,6.7970243 19.0069431,9.00520507 L19.0069431,15.001735 C19.0069431,17.2099158 17.2160821,19 15.0069431,19 L3.00694311,19 C0.797804106,19 -0.993056895,17.2099158 -0.993056895,15.001735 L-0.993056895,8.99826498 C-0.993056895,6.7900842 0.797804106,5 3.00694311,5 L4.00694793,5 C4.55923268,5 5.00694793,5.44752105 5.00694793,5.99956624 C5.00694793,6.55161144 4.55923268,6.99913249 4.00694793,6.99913249 L3.00694311,6.99913249 C1.90237361,6.99913249 1.00694311,7.89417459 1.00694311,8.99826498 L1.00694311,15.001735 C1.00694311,16.1058254 1.90237361,17.0008675 3.00694311,17.0008675 L15.0069431,17.0008675 C16.1115126,17.0008675 17.0069431,16.1058254 17.0069431,15.001735 L17.0069431,9.00520507 C17.0069431,7.90111468 16.1115126,7.00607258 15.0069431,7.00607258 L14.0069431,7.00607258 Z" fill="#fff" fill-rule="nonzero" opacity="0.3" transform="translate(9.006943, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-9.006943, -12.000000) " />
								<rect fill="#ff4db8" opacity="0.3" transform="translate(14.000000, 12.000000) rotate(-270.000000) translate(-14.000000, -12.000000) " x="13" y="6" width="2" height="12" rx="1" />
								<path d="M21.7928932,9.79289322 C22.1834175,9.40236893 22.8165825,9.40236893 23.2071068,9.79289322 C23.5976311,10.1834175 23.5976311,10.8165825 23.2071068,11.2071068 L20.2071068,14.2071068 C19.8165825,14.5976311 19.1834175,14.5976311 18.7928932,14.2071068 L15.7928932,11.2071068 C15.4023689,10.8165825 15.4023689,10.1834175 15.7928932,9.79289322 C16.1834175,9.40236893 16.8165825,9.40236893 17.2071068,9.79289322 L19.5,12.0857864 L21.7928932,9.79289322 Z" fill="#fff" fill-rule="nonzero" transform="translate(19.500000, 12.000000) rotate(-90.000000) translate(-19.500000, -12.000000) " />
							</g>
						</svg>
					</span>
					<span>Logout</span>
				</a>
				<form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
					{{ csrf_field() }}
				</form>
			</li>
			@if(Auth::user()->access_1=='on')
			<li class="nav-label">ACCESS</li>
			<li class="nav-color">
				<a class="nav-link" id="btn_klik" href="{{ url('/mapping_shift/dashboard/') }}">
					<span class="dz-icon bg-blue light">
						<i class="fa-solid fa-users"></i>
					</span>
					<span>Mapping Shift Kuli</span>
				</a>
			</li>
			@endif
			<li class="nav-label">Tema</li>
			<!-- <li class="nav-color" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">
				<a class="nav-link">
					<span class="dz-icon bg-blue light">
						<i class="fa-solid fa-palette"></i>
					</span>
					<span>Highlights</span>
				</a>
			</li> -->
			<li>
				<div class="mode">
					<span class="dz-icon bg-green light">
						<i class="fa-solid fa-moon"></i>
					</span>
					<span>Dark Mode</span>
					<div class="custom-switch">
						<input type="checkbox" class="switch-input theme-btn" id="toggle-dark-menu">
						<label class="custom-switch-label" for="toggle-dark-menu"></label>
					</div>
				</div>
			</li>
		</ul>
		<div class="sidebar-bottom">
			<h6 class="name">HRD-APP</h6>
			<p>Version 1.0</p>
		</div>
	</div>
	<!-- Sidebar End -->