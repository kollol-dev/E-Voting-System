<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-12">
				<div class="card">
					<div class="card-header card-header-tabs card-header-primary">
						<div class="nav-tabs-navigation">
							<div class="nav-tabs-wrapper">
								<h4 class="card-title">Customers Point</h4>
								<ul
									v-if="allCustomers.total == 0"
									class="nav nav-tabs"
									data-tabs="tabs"
								></ul>
							</div>
						</div>
					</div>
					<div v-if="allCustomers" class="card-body">
						<div class="d-flex justify-content-center" v-if="cardLoading">
							<Loader />
						</div>
						<div
							class="d-flex justify-content-center"
							v-if="!cardLoading && allCustomers.length == 0"
						>
							<p>No data</p>
						</div>

						<form
							v-if="!cardLoading && allCustomers.length > 0"
							v-on:submit.prevent="createPoint"
						>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="bmd-label-floating">Customer</label>

										<Select v-model="pointObj.customer_id" clearable filterable>
											<Option
												v-for="item in allCustomers"
												:value="item.id"
												:key="'pp' + item.uid"
												><img
													style="height: 30px; width: 30px; margin-right: 8px"
													:src="item.profilePic"
													alt=""
												/>{{ item.name }} (#{{ item.uid }})</Option
											>
										</Select>
										<p v-if="error.customer_id" class="text-danger">
											*{{ error.customer_id }}
										</p>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label class="bmd-label-floating">Point</label>
										<Select v-model="pointObj.type" clearable filterable>
											<Option value="cash-in">Cash In</Option>
											<Option value="bic">BIC</Option>
											<!-- <Option value="bic">BIC</Option> -->
										</Select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mt-3">
									<div class="form-group">
										<label class="bmd-label-floating">Point</label>
										<input
											v-model="pointObj.point"
											type="number"
											class="form-control"
										/>
										<p v-if="error.point" class="text-danger">
											*{{ error.point }}
										</p>
									</div>
								</div>
							</div>
							<button
								type="submit"
								class="btn btn-primary pull-right d-flex align-items-center"
								:disabled="btnLoading"
							>
								<template v-if="btnLoading">
									<Loader position="inline" color="white" />
									<!-- <p class="text-center">Adding</p> -->
								</template>
								<template v-else>
									<p>Add</p>
								</template>
							</button>
							<div class="clearfix"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
	data() {
		return {
			allCustomers: [],
			cardLoading: true,
			btnLoading: false,
			pointObj: {
				customer_id: null,
				point: null,
				type: "cash-in",
			},

			error: {
				customer_id: false,
				point: false,
			},
		};
	},
	computed: {
		...mapGetters({}),
	},
	methods: {
		clearData() {
			this.pointObj = {
				customer_id: null,
				point: null,
			};
			this.btnLoading = false;
		},

		clearErrorData() {
			this.error = {
				customer_id: false,
				point: false,
			};
		},

		async createPoint() {
			this.clearErrorData();
			if (this.pointObj.customer_id == null) {
				return (this.error.customer_id = "Please select a customer!");
			}
			if (this.pointObj.point == null || this.pointObj.point.trim() == "") {
				return (this.error.point = "Point field is required!");
			}
			this.btnLoading = true;
			const res = await this.callApi(
				"post",
				"/app/admin/update/customer/point",
				this.pointObj
			);
			if (res.status == 201) {
				this.pointObj.customer_id = null;
				this.pointObj.point = null;
				this.ns("Point added!");
			} else {
				this.swr();
			}
			this.btnLoading = false;
		},
	},
	async created() {
		this.cardLoading = true;
		const customers = await this.callApi("get", "/app/customer/get/all");
		if (customers.status == 200) {
			this.allCustomers = customers.data;
		} else {
			this.nswr();
		}
		this.cardLoading = false;
	},
};
</script>