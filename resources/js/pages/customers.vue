<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-12">
				<div class="card">
					<div class="card-header card-header-tabs card-header-primary">
						<div class="nav-tabs-navigation">
							<div class="nav-tabs-wrapper">
								<h4 class="card-title">Customers</h4>
								<ul
									v-if="allCustomers.total == 0"
									class="nav nav-tabs"
									data-tabs="tabs"
								>
									<li class="nav-item">
										<a class="nav-link active" @click="createModal = true">
											<i class="material-icons">person</i> Add Customer
											<div class="ripple-container"></div>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div v-if="allCustomers" class="card-body table-responsive">
						<table class="table table-hover">
							<thead class="text-warning">
								<th>ID</th>
								<th>Name</th>
								<th>UID</th>
								<th>NID</th>
								<th>Sponsord By</th>
								<th>Mobile</th>
								<th>Country</th>
								<th>Division</th>
								<th>District</th>
								<th>Upazila</th>
								<th>City</th>
								<th>Ward</th>
								<th>Area</th>
								<th>Action</th>
							</thead>
							<tbody>
								<tr
									v-if="allCustomers.total > 0 && !tableLoading"
									v-for="(item, index) in allCustomers.data"
									:key="'ad' + index"
								>
									<td>{{ item.id }}</td>

									<td style="min-width: 300px">
										<img
											style="width: 75px; height: 75px"
											:src="item.profilePic"
											alt=""
										/>
										<span>{{ item.name }}</span>
									</td>
									<td>{{ item.uid }}</td>
									<td>{{ item.nid }}</td>
									<td>
										{{ item.sponsored_by ? item.sponsored_by.name : "N/A" }}
									</td>
									<td>{{ item.mobile }}</td>
									<td>{{ item.country }}</td>
									<td>{{ item.division }}</td>
									<td>{{ item.district }}</td>
									<td>{{ item.upazila }}</td>
									<td>{{ item.city }}</td>
									<td>{{ item.ward }}</td>
									<td>{{ item.area }}</td>
									<td>
										<div
											@click="openEditModal(index)"
											:disabled="deleteLoading && deleteIndex == index"
											class="btn btn-default"
										>
											Edit
										</div>
										<Poptip
											confirm
											title="Are you sure you want to delete this?"
											@on-ok="deleteCustomer(index)"
											ok-text="Delete"
										>
											<!-- <div
												:class="
													deleteLoading && deleteIndex == index
														? 'btn btn-primary btn-loading'
														: 'btn btn-primary'
												"
											> -->
											<div
												:class="
													deleteLoading && deleteIndex == index
														? 'btn btn-primary btn-loading'
														: 'btn btn-primary'
												"
											>
												<Loader
													v-if="deleteLoading && deleteIndex == index"
													position="inline"
													color="#999999"
												/>
												<p v-else class="text-center">Delete</p>
											</div>
										</Poptip>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="d-flex justify-content-center" v-if="tableLoading">
							<Loader />
						</div>
						<div
							class="d-flex justify-content-center"
							v-if="!tableLoading && allCustomers.total == 0"
						>
							<p>No data</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- create modal -->
		<Modal
			title="Add customer"
			v-model="createModal"
			:footer-hide="true"
			:mask-closable="false"
			:closable="false"
		>
			<form v-on:submit.prevent="addCustomer">
				<div class="row">
					<div class="col-12">
						<div class="form-group">
							<label class="bmd-label-floating">Name</label>
							<input v-model="customer.name" type="text" class="form-control" />
							<p v-if="error.name" class="text-danger">*{{ error.name }}</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Mobile</label>
							<input
								v-model="customer.mobile"
								type="text"
								class="form-control"
							/>
							<p v-if="error.mobile" class="text-danger">*{{ error.mobile }}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating d-block">Profile Picture</label>
							<div v-if="uploadFile" class="demo-upload-list">
								<template v-if="uploadFile.status === 'finished'">
									<img :src="uploadFile.response.url" />
									<div class="demo-upload-list-cover">
										<Icon
											type="ios-trash-outline"
											@click.native="handleRemove(item)"
										></Icon>
									</div>
								</template>
								<template v-else>
									<Progress
										v-if="uploadFile.showProgress"
										:percent="uploadFile.percentage"
										hide-info
									></Progress>
								</template>
							</div>
							<Upload
								v-else
								:show-upload-list="false"
								:on-progress="handleProgress"
								:format="['jpg', 'jpeg', 'png']"
								:max-size="20480"
								:on-format-error="handleFormatError"
								:on-exceeded-size="handleMaxSize"
								type="drag"
								name="file"
								action="/app/upload/file"
								style="display: inline-block; width: 58px"
							>
								<div style="width: 58px; height: 58px; line-height: 58px">
									<Icon type="ios-camera" size="20"></Icon>
								</div>
							</Upload>
							<p v-if="error.profilePic" class="text-danger">
								*{{ error.profilePic }}
							</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Nid</label>
							<input v-model="customer.nid" type="text" class="form-control" />
							<p v-if="error.nid" class="text-danger">*{{ error.nid }}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Country</label>
							<input
								v-model="customer.country"
								type="text"
								class="form-control"
							/>
							<p v-if="error.country" class="text-danger">
								*{{ error.country }}
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Division</label>
							<Select v-model="customer.division" clearable filterable>
								<Option
									v-for="item in divisions"
									:value="item.name"
									:key="'dv' + item.name"
									>{{ item.name }}</Option
								>
							</Select>
							<p v-if="error.division" class="text-danger">
								*{{ error.division }}
							</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">District</label>
							<Select v-model="customer.district" clearable filterable>
								<Option
									v-for="item in districts"
									:value="item.name"
									:key="'ds' + item.name"
									>{{ item.name }}</Option
								>
							</Select>
							<p v-if="error.district" class="text-danger">
								*{{ error.district }}
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Upazila</label>
							<Select v-model="customer.upazila" clearable filterable>
								<Option
									v-for="(item, index) in upazilas"
									:value="item.name"
									:key="'up' + index"
									>{{ item.name }}</Option
								>
							</Select>
							<p v-if="error.upazila" class="text-danger">
								*{{ error.upazila }}
							</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">City</label>
							<Select v-model="customer.city" clearable filterable>
								<Option
									v-for="(item, index) in cities"
									:value="item.city_name"
									:key="'ct' + index"
									>{{ item.city_name }}</Option
								>
							</Select>
							<p v-if="error.city" class="text-danger">*{{ error.city }}</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Ward</label>
							<Select v-model="customer.ward" clearable filterable>
								<Option
									v-for="(item, index) in wards"
									:value="item.ward_name"
									:key="'wd' + index"
									>{{ item.ward_name }}</Option
								>
							</Select>
							<p v-if="error.ward" class="text-danger">*{{ error.ward }}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Area</label>
							<Select v-model="customer.area" clearable filterable>
								<Option
									v-for="(item, index) in areas"
									:value="item.area_name"
									:key="'ar' + index"
									>{{ item.area_name }}</Option
								>
							</Select>
							<p v-if="error.area" class="text-danger">*{{ error.area }}</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="form-group">
							<label class="bmd-label-floating">Password</label>
							<input
								v-model="customer.password"
								type="password"
								class="form-control"
							/>
							<p v-if="error.password" class="text-danger">
								*{{ error.password }}
							</p>
						</div>
					</div>
				</div>
				<button
					type="submit"
					class="btn btn-primary pull-right d-flex align-items-center"
					:disabled="modal_loading"
				>
					<template v-if="modal_loading">
						<Loader position="inline" color="white" />
						<!-- <p class="text-center">Adding</p> -->
					</template>
					<template v-else>
						<p>Add</p>
					</template>
				</button>
				<button
					type="button"
					@click="closeModal"
					class="btn btn-default pull-right"
				>
					<p>Cancel</p>
				</button>
				<div class="clearfix"></div>
			</form>
		</Modal>
		<!-- create modal end -->

		<!-- edit modal -->
		<Modal
			title="Edit customer"
			v-model="editModal"
			:footer-hide="true"
			:mask-closable="false"
			:closable="false"
		>
			<form v-on:submit.prevent>
				<div class="row">
					<div class="col-12">
						<div class="form-group">
							<label class="bmd-label-floating">Name</label>
							<input v-model="editData.name" type="text" class="form-control" />
							<p v-if="error.name" class="text-danger">*{{ error.name }}</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Mobile</label>
							<input
								v-model="editData.mobile"
								type="text"
								class="form-control"
							/>
							<p v-if="error.mobile" class="text-danger">*{{ error.mobile }}</p>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Nid</label>
							<input v-model="editData.nid" type="text" class="form-control" />
							<p v-if="error.nid" class="text-danger">*{{ error.nid }}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Country</label>
							<input
								v-model="editData.country"
								type="text"
								class="form-control"
							/>
							<p v-if="error.country" class="text-danger">
								*{{ error.country }}
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Division</label>
							<Select v-model="editData.division" clearable filterable>
								<Option
									v-for="item in divisions"
									:value="item.name"
									:key="'dv' + item.name"
									>{{ item.name }}</Option
								>
							</Select>
							<p v-if="error.division" class="text-danger">
								*{{ error.division }}
							</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">District</label>
							<Select v-model="editData.district" clearable filterable>
								<Option
									v-for="item in districts"
									:value="item.name"
									:key="'ds' + item.name"
									>{{ item.name }}</Option
								>
							</Select>
							<p v-if="error.district" class="text-danger">
								*{{ error.district }}
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Upazila</label>
							<Select v-model="editData.upazila" clearable filterable>
								<Option
									v-for="(item, index) in upazilas"
									:value="item.name"
									:key="'up' + index"
									>{{ item.name }}</Option
								>
							</Select>
							<p v-if="error.upazila" class="text-danger">
								*{{ error.upazila }}
							</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">City</label>
							<Select v-model="editData.city" clearable filterable>
								<Option
									v-for="(item, index) in cities"
									:value="item.city_name"
									:key="'ct' + index"
									>{{ item.city_name }}</Option
								>
							</Select>
							<p v-if="error.city" class="text-danger">*{{ error.city }}</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Ward</label>
							<Select v-model="editData.ward" clearable filterable>
								<Option
									v-for="(item, index) in wards"
									:value="item.ward_name"
									:key="'wd' + index"
									>{{ item.ward_name }}</Option
								>
							</Select>
							<p v-if="error.ward" class="text-danger">*{{ error.ward }}</p>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating">Area</label>
							<Select v-model="editData.area" clearable filterable>
								<Option
									v-for="(item, index) in areas"
									:value="item.area_name"
									:key="'ar' + index"
									>{{ item.area_name }}</Option
								>
							</Select>
							<p v-if="error.area" class="text-danger">*{{ error.area }}</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="bmd-label-floating d-block">Profile Picture</label>

							<div v-if="editData.profilePic" class="demo-upload-list">
								<img :src="editData.profilePic" alt="" />
								<div class="demo-upload-list-cover">
									<Icon
										type="ios-trash-outline"
										@click.native="editData.profilePic = null"
									></Icon>
								</div>
							</div>

							<div v-if="uploadFile" class="demo-upload-list">
								<template v-if="uploadFile.status === 'finished'">
									<img :src="uploadFile.response.url" />
									<div class="demo-upload-list-cover">
										<Icon
											type="ios-trash-outline"
											@click.native="handleRemove()"
										></Icon>
									</div>
								</template>
								<template v-else>
									<Progress
										v-if="uploadFile.showProgress"
										:percent="uploadFile.percentage"
										hide-info
									></Progress>
								</template>
							</div>
							<Upload
								v-else-if="!editData.profilePic"
								:show-upload-list="false"
								:on-progress="handleProgress"
								:format="['jpg', 'jpeg', 'png']"
								:max-size="20480"
								:on-format-error="handleFormatError"
								:on-exceeded-size="handleMaxSize"
								type="drag"
								name="file"
								action="/app/upload/file"
								style="display: inline-block; width: 58px"
							>
								<div style="width: 58px; height: 58px; line-height: 58px">
									<Icon type="ios-camera" size="20"></Icon>
								</div>
							</Upload>
							<p v-if="error.profilePic" class="text-danger">
								*{{ error.profilePic }}
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="form-group" style="">
							<template v-if="isClickedChangePassword">
								<label class="bmd-label">New Password</label>
								<input
									v-model="editData.password"
									type="password"
									class="form-control"
								/>
								<p v-if="error.password" class="text-danger">
									*{{ error.password }}
								</p>
							</template>
							<p
								@click="isClickedChangePassword = !isClickedChangePassword"
								class="btn btn-primary"
							>
								{{ isClickedChangePassword ? "Cancel" : "Change Password" }}
							</p>
						</div>
					</div>
				</div>
				<button
					@click="editCustomer"
					class="btn btn-primary pull-right d-flex align-items-center"
					:disabled="modal_loading"
				>
					<template v-if="modal_loading">
						<Loader position="inline" color="white" />
						<!-- <p class=".text-center">Adding</p> -->
					</template>
					<template v-else>
						<p>Edit</p>
					</template>
				</button>
				<button
					type="button"
					@click="closeModal"
					class="btn btn-default pull-right"
				>
					<p>Cancel</p>
				</button>
				<div class="clearfix"></div>
			</form>
		</Modal>
		<!-- edit modal end  -->
	</div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
	data() {
		return {
			allCustomers: [],
			tableLoading: true,
			createModal: false,
			editModal: false,
			modal_loading: false,
			customer: {
				profilePic: "",
				name: "",
				nid: "",
				mobile: "",
				country: "Bangladesh",
				division: "",
				district: "",
				upazila: "",
				city: "",
				ward: "",
				area: "",
				password: "",
				role: "Customer",
			},
			error: {
				profilePic: false,
				name: false,
				nid: false,
				mobile: false,
				country: false,
				division: false,
				district: false,
				upazila: false,
				city: false,
				ward: false,
				area: false,
				password: false,
			},
			editIndex: -1,
			deleteIndex: -1,
			deleteLoading: false,
			isClickedChangePassword: false,
			editData: {},
			page: 1,

			uploadFile: false,
		};
	},
	computed: {
		...mapGetters({}),
	},
	methods: {
		clearData() {
			this.customer = {
				profilePic: "",
				name: "",
				nid: "",
				mobile: "",
				country: "",
				division: "",
				district: "",
				upazila: "",
				city: "",
				ward: "",
				area: "",
				password: "",
				role: "Customer",
			};
			this.editIndex = -1;
			this.editData = {};
			this.isClickedChangePassword = false;
		},

		clearErrorData() {
			this.error = {
				profilePic: false,
				name: false,
				nid: false,
				mobile: false,
				country: false,
				division: false,
				district: false,
				upazila: false,
				city: false,
				ward: false,
				area: false,
				password: false,
			};
		},

		openModal() {
			this.createModal = true;
		},

		openEditModal(index) {
			this.clearErrorData();
			this.editModal = true;
			this.editData = _.clone(this.allCustomers.data[index]);
			this.editIndex = _.clone(index);
		},

		closeModal() {
			this.clearErrorData();
			this.createModal = false;
			this.modal_loading = false;
			this.editIndex = -1;
			this.editData = {};
			this.editModal = false;
		},

		async editCustomer() {
			if (!this.validateCustomerEditData()) return;

			this.editData.profilePic = this.uploadFile
				? _.clone(this.uploadFile.response.url)
				: this.editData.profilePic;
			this.uploadFile = false;

			this.modal_loading = true;
			const res = await this.callApi(
				"post",
				`/app/admin/edit/customer/${this.editData.id}`,
				this.editData
			);
			if (res.status == 200) {
				this.allCustomers.data[this.editIndex] = _.clone(this.editData);
				this.editData = {};
				this.editIndex = -1;
			} else {
				this.nswr();
			}
			this.clearData();
			this.closeModal();
		},

		async deleteCustomer(index) {
			this.deleteLoading = true;
			this.deleteIndex = index;
			const res = await this.callApi(
				"post",
				`/app/admin/delete/customer/${this.allCustomers.data[index].id}`
			);
			if (res.status == 200) {
				this.allCustomers.data.splice(index, 1);
			} else {
				this.nswr();
			}
			this.deleteIndex = -1;
			this.deleteLoading = false;
		},

		async addCustomer() {
			this.clearErrorData();
			if (!this.validateCustomer()) {
				return;
			}
			this.modal_loading = true;
			this.customer.profilePic = _.clone(this.uploadFile.response.url);
			this.uploadFile = false;

			const res = await this.callApi(
				"post",
				"/app/admin/create/customer/new",
				this.customer
			);
			if (res.status == 201) {
				this.allCustomers.data.unshift(res.data);
				this.allCustomers.total += 1;

				let obj = _.clone(this.allCustomers);
			} else {
				this.nswr();
			}
			this.modal_loading = false;
			this.closeModal();
		},

		async paginate(page) {
			this.tableLoading = true;
			const res = await this.callApi(
				"get",
				`/app/admin/get/customer/paginate?page=${this.page}`
			);
			if (res.status == 200) {
				this.allCustomers = res.data;
			} else {
				this.nswr();
			}
			this.tableLoading = false;
		},

		validateCustomer() {
			if (this.customer.name.trim() == "") {
				this.error.name = "Name is required!";
				return false;
			}

			if (this.customer.mobile.trim() == "") {
				this.error.mobile = "Mobile number is required!";
				return false;
			}

			if (this.uploadFile == "") {
				this.error.profilePic = "Profile Picture is required!";
				return false;
			}

			if (this.customer.nid.trim() == "") {
				this.error.nid = "NID is required!";
				return false;
			}

			if (this.customer.country.trim() == "") {
				this.error.country = "Country is required!";
				return false;
			}

			if (this.customer.division.trim() == "") {
				this.error.division = "Division is required!";
				return false;
			}

			if (this.customer.district.trim() == "") {
				this.error.district = "District is required!";
				return false;
			}

			if (this.customer.upazila.trim() == "") {
				this.error.upazila = "Upazila is required!";
				return false;
			}

			if (this.customer.city.trim() == "") {
				this.error.city = "City is required!";
				return false;
			}
			if (this.customer.ward.trim() == "") {
				this.error.ward = "Ward is required!";
				return false;
			}

			if (this.customer.area.trim() == "") {
				this.error.area = "Area is required!";
				return false;
			}

			if (this.customer.password.trim() == "") {
				this.error.password = "Password is required!";
				return false;
			}

			if (this.customer.password.length < 6) {
				this.error.password = "Password length should be at least 6!";
				return false;
			}

			return true;
		},

		validateCustomerEditData() {
			this.clearErrorData();
			if (!this.editData.name || this.editData.name.trim() == "") {
				this.error.name = "Full name is required!";
				return false;
			}

			if (!this.editData.mobile || this.editData.mobile.trim() == "") {
				this.error.mobile = "Mobile number is required!";
				return false;
			}

			if (!this.editData.profilePic && !this.uploadFile) {
				this.error.profilePic = "Please upload one profile picture!";
				return false;
			}

			if (!this.editData.nid || this.editData.nid.trim() == "") {
				this.error.nid = "NID is required!";
				return false;
			}

			if (!this.editData.country || this.editData.country.trim() == "") {
				this.error.country = "Country is required!";
				return false;
			}

			if (!this.editData.division || this.editData.division.trim() == "") {
				this.error.division = "Division is required!";
				return false;
			}

			if (!this.editData.district || this.editData.district.trim() == "") {
				this.error.district = "District is required!";
				return false;
			}

			if (!this.editData.upazila || this.editData.upazila.trim() == "") {
				this.error.upazila = "Upazila is required!";
				return false;
			}
			if (!this.editData.city || this.editData.city.trim() == "") {
				this.error.city = "City is required!";
				return false;
			}
			if (!this.editData.ward || this.editData.ward.trim() == "") {
				this.error.ward = "Ward is required!";
				return false;
			}
			if (!this.editData.area || this.editData.area.trim() == "") {
				this.error.area = "Area is required!";
				return false;
			}

			if (this.isClickedChangePassword) {
				if (!this.editData.password || this.editData.password.trim() == "") {
					this.error.password = "Password is required!";
					return false;
				}
				if (!this.editData.password || this.editData.password.length < 6) {
					this.error.password = "Password length should be at least 6!";
					return false;
				}
			}
			return true;
		},

		handleRemove() {
			this.uploadFile = false;
		},

		handleProgress(res, file) {
			this.uploadFile = file;
		},

		handleFormatError(file) {
			this.$Notice.warning({
				title: "The file format is incorrect",
				desc:
					"File format of " +
					file.name +
					" is incorrect, please select jpg or png.",
			});
		},

		handleMaxSize(file) {
			this.$Notice.warning({
				title: "Exceeding file size limit",
				desc: "File  " + file.name + " is too large, no more than 2M.",
			});
		},
	},
	async created() {
		this.paginate(1);
		const [cities, wards, areas] = await Promise.all([
			this.callApi("get", "/app/admin/get/city/all"),
			this.callApi("get", "/app/admin/get/ward/all"),
			this.callApi("get", "/app/admin/get/area/all"),
		]);
		if (cities.status == 200 && wards.status == 200 && areas.status == 200) {
			this.$store.commit("setCities", cities.data);
			this.$store.commit("setWards", wards.data);
			this.$store.commit("setAreas", areas.data);
		} else {
			this.nswr();
		}
	},
};
</script>

<style >
.btn-loading {
	cursor: not-allowed;
	opacity: 0.5;
}
</style>