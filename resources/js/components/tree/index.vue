<template>
	<div>
		<div v-if="!node.isEmpty" @click="changeTree(node.id)" class="child_box">
			<img v-if="node.profilePic" :src="node.profilePic" alt="" />
			<div v-if="node" class="box_popup">
				<div style="min-height: 100px">
					<p>Total: {{ totalChild }}</p>
					<p>Left: {{ totalLeft }}</p>
					<p>Right: {{ totalRight }}</p>
					<p>UID: {{ node.uid }}</p>
				</div>
			</div>
			<span v-if="node.name">{{ node.name }}</span>
		</div>

		<template v-else>
			<div @click="createModal = true" class="child_box">
				<img src="/img/plus.png" alt="" />
			</div>
		</template>

		<ul v-if="node.children && node.children.length > 0">
			<li v-for="(item, index) in node.children" :key="item.name + index">
				<tree :node="item" :parent="node"></tree>
				<!-- <p>{{ item.id }}</p> -->
			</li>
		</ul>

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
							<!-- <input v-model="customer.uid" type="text" class="form-control" /> -->
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
	</div>
</template>

<script>
export default {
	props: ["node", "parent"],
	name: "tree",

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

			uploadFile: false,
		};
	},

	computed: {
		totalLeft() {
			let total = 0;
			let index = 0;
			if (this.node.children) {
				for (let i of this.node.children) {
					if (!i.isEmpty && index % 2 == 0) {
						console.log("id", i.id, "t", i.total_transaction);
						total += i.total_transaction ? i.total_transaction : 0;
					}
					for (let j in i.children) {
						if (!i.children[j].isEmpty && j % 2 == 0) {
							console.log("j", i.children[j]);
							total += i.children[j].total_transaction
								? i.children[j].total_transaction
								: 0;
						}
					}
					index++;
				}
			}
			return total;
		},
		totalRight() {
			let total = 0;
			let index = 0;
			if (this.node.children) {
				for (let i of this.node.children) {
					if (!i.isEmpty && index % 2 == 1) {
						console.log("id", i.id, "t", i.total_transaction);
						total += i.total_transaction ? i.total_transaction : 0;
					}
					for (let j in i.children) {
						if (!i.children[j].isEmpty && j % 1 == 0) {
							console.log("j", i.children[j]);
							total += i.children[j].total_transaction
								? i.children[j].total_transaction
								: 0;
						}
					}
					index++;
				}
			}
			return total;
		},

		totalChild() {
			let total = 0;
			if (this.node.children) {
				let left = !this.node.children[0].isEmpty
					? this.node.children[0].total_transaction
					: 0;
				let right = !this.node.children[1].isEmpty
					? this.node.children[1].total_transaction
					: 0;
				total = left + right;
			}
			total += this.node.total_transaction;

			return total;
		},
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

		closeModal() {
			this.clearErrorData();
			this.createModal = false;
			this.modal_loading = false;
		},

		async addCustomer() {
			this.clearErrorData();
			if (!this.validateCustomer()) {
				return;
			}
			this.modal_loading = true;
			this.customer.profilePic = _.clone(this.uploadFile.response.url);
			this.uploadFile = false;

			this.customer.parent = this.parent;
			this.customer.position = this.parent.children[0].isEmpty ? "lft" : "rght";

			const res = await this.callApi(
				"post",
				"/app/customer/create/customer/new",
				this.customer
			);
			if (res.status == 201) {
				console.log("node", this.node);
				this.node = res.data.customer;
				this.node.isEmpty = false;
				// this.node.push(res.data);
			} else {
				this.nswr();
			}
			this.modal_loading = false;
			this.closeModal();
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

		changeTree(id) {
			this.$router.push(`/tree/${id}`);
			// window.location.href = `/tree/${id}`;
		},
	},
};
</script>

<style>
ul {
	padding-left: 0px !important;
}
.tree {
	width: 100%;
	height: auto;
	text-align: center;
}
.tree ul {
	padding-top: 20px;
	position: relative;
	transition: 0.5s;
	padding-left: 0px !important;
}
.tree ul li ul {
	padding-left: 0px !important;
}
.tree li {
	display: inline-table;
	text-align: center;
	list-style-type: none;
	position: relative;
	padding: 10px;
	transition: 0.5s;
	overflow: auto;
	white-space: nowrap;
}
.tree li::before,
.tree li::after {
	content: "";
	position: absolute;
	top: 0;
	right: 50%;
	border-top: 1px solid #ccc;
	width: 51%;
	height: 10px;
}
.tree li::after {
	right: auto;
	left: 50%;
	border-left: 1px solid #ccc;
}
.tree li:only-child::after,
.tree li:only-child::before {
	display: none;
}
.tree li:only-child {
	padding-top: 0;
}
.tree li:first-child::before,
.tree li:last-child::after {
	border: 0 none;
}
.tree li:last-child::before {
	border-right: 1px solid #ccc;
	border-radius: 0 5px 0 0;
	-webkit-border-radius: 0 5px 0 0;
	-moz-border-radius: 0 5px 0 0;
}
.tree li:first-child::after {
	border-radius: 5px 0 0 0;
	-webkit-border-radius: 5px 0 0 0;
	-moz-border-radius: 5px 0 0 0;
}
.tree ul::before {
	content: "";
	position: absolute;
	top: 0;
	left: 50%;
	border-left: 1px solid #ccc;
	width: 0;
	height: 20px;
}
.tree li .child_box {
	border: 1px solid #ccc;
	padding: 10px;
	display: inline-grid;
	border-radius: 5px;
	text-decoration-line: none;
	border-radius: 5px;
	transition: 0.5s;
	min-width: 200px;
}
.tree li img {
	width: 50px;
	height: 50px;
	margin-bottom: 10px !important;
	border-radius: 100px;
	margin: auto;
}
.tree li span {
	border: 1px solid #ccc;
	border-radius: 5px;
	color: #666;
	padding: 8px;
	font-size: 12px;
	text-transform: uppercase;
	letter-spacing: 1px;
	font-weight: 500;
}
/*Hover-Section*/
.tree li .child_box:hover,
.tree li .child_box:hover i,
.tree li .child_box:hover span,
.tree li .child_box:hover ~ ul li .child_box {
	background: #c8e4f8;
	color: #000;
	border: 1px solid #94a0b4;
}
.tree li .child_box:hover + ul li::after,
.tree li .child_box:hover + ul li::before,
.tree li .child_box:hover + ul::before,
.tree li .child_box:hover + ul ul::before {
	border-color: #94a0b4;
}
.box_popup {
	display: none;
}
img {
	cursor: pointer;
}
img:hover ~ .box_popup {
	position: absolute;
	width: 120px;
	background: #4d4d4d;
	color: white;
	border-radius: 8px;
	/* line-height: 2px; */
	display: inline-block;
	z-index: 999;
	padding: 5px;
	margin-left: 136px;
	top: 0;
}
img:hover ~ .box_popup:before {
	content: "";
	position: absolute;
	top: 50%;
	margin-top: -15px;
	left: -20px;
	border: solid 10px transparent;
	border-right-color: #4d4d4d;
	z-index: 1;
}
.tree
	> li
	> div
	> ul
	> li:last-child
	> div
	> ul
	> li:last-child
	img:hover
	~ .box_popup {
	position: absolute;
	width: 120px;
	background: #4d4d4d;
	color: white;
	border-radius: 8px;
	/* line-height: 2px; */
	display: inline-block;
	z-index: 999;
	margin-left: 0px;
	top: -115px;
	transition: 0.5s;
	cursor: pointer;
}
.tree
	> li
	> div
	> ul
	> li:last-child
	> div
	> ul
	> li:last-child
	img:hover
	~ .box_popup:before {
	content: "";
	position: absolute;
	top: 114%;
	left: 40%;
	border: solid 10px transparent;
	border-top-color: #4d4d4d;
	z-index: 1;
	/* background: red !important; */
}
</style>