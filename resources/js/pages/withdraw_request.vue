<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12 col-md-12">
				<!-- <div class="card"> -->
				<!-- <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Send amount Withdraw Request</h4>
              </div>
            </div>
          </div> -->
				<div class="card-body">
					<form v-on:submit.prevent="createWithdrawRequest">
						<div class="row">
							<div class="col-4 mt-3">
								<div class="form-group">
									<label class="bmd-label-floating">Amount</label>
									<input
										v-model="withdraw.amount"
										type="number"
										class="form-control"
									/>
									<p v-if="error.amount" class="text-danger">
										<Icon type="ios-alert" />
										{{ error.amount }}
									</p>
								</div>
							</div>
							<div class="col-4 mt-3">
								<div class="form-group">
									<label class="bmd-label-floating">Admin</label>
									<Select v-model="withdraw.admin_id" clearable filterable>
										<Option
											v-for="item in admins"
											:value="item.id"
											:key="'admins' + item.uid"
											><img
												style="height: 30px; width: 30px; margin-right: 8px"
												:src="item.profilePic"
												alt=""
											/>{{ item.name }}</Option
										>
									</Select>
									<p v-if="error.admin_id" class="text-danger">
										<Icon type="ios-alert" />
										{{ error.admin_id }}
									</p>
								</div>
							</div>
							<div class="col-4 mt-3">
								<div class="form-group">
									<label class="bmd-label-floating">Password</label>
									<input
										v-model="withdraw.password"
										type="password"
										class="form-control"
									/>
									<p v-if="error.password" class="text-danger">
										<Icon type="ios-alert" />
										{{ error.password }}
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
							</template>
							<template v-else>
								<p>Send Withdraw Request</p>
							</template>
						</button>
						<div class="clearfix"></div>
					</form>
				</div>
				<!-- </div> -->

				<div class="card">
					<div class="card-header card-header-tabs card-header-primary">
						<div class="nav-tabs-navigation">
							<div class="nav-tabs-wrapper">
								<h4 class="card-title">Withdraw History</h4>
								<ul
									v-if="allWithdrawHistoy.length == 0"
									class="nav nav-tabs"
									data-tabs="tabs"
								></ul>
							</div>
						</div>
					</div>
					<div class="card-body">
						<table class="table table-hover">
							<thead class="text-warning">
								<th>NO.</th>
								<th>ID.</th>
								<th>Date</th>
								<th>Amount</th>
								<th>Admin</th>
								<th>Status</th>
							</thead>
							<tbody>
								<tr
									v-if="allWithdrawHistoy.length > 0 && !cardLoading"
									v-for="(item, index) in allWithdrawHistoy"
									:key="'wd' + index"
								>
									<td>{{ index + 1 }}</td>
									<td>{{ item.id }}</td>
									<td>{{ item.created_at | formatDateTime }}</td>
									<td>{{ item.amount }}</td>
									<td>{{ item.admin ? item.admin.name : "N/A" }}</td>
									<td>{{ item.status }}</td>
								</tr>
							</tbody>
						</table>
						<div class="d-flex justify-content-center" v-if="cardLoading">
							<Loader />
						</div>
						<div
							class="d-flex justify-content-center"
							v-if="!cardLoading && allWithdrawHistoy.length == 0"
						>
							<p>No data</p>
						</div>
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
			allWithdrawHistoy: [],
			admins: [],
			cardLoading: true,
			btnLoading: false,
			withdraw: {
				amount: null,
				admin_id: null,
				password: "",
			},

			error: {
				amount: false,
				admin_id: false,
				password: false,
			},
		};
	},
	computed: {
		...mapGetters({}),
	},
	methods: {
		clearData() {
			this.withdraw = {
				amount: null,
				admin_id: null,
				password: "",
			};
			this.btnLoading = false;
		},

		clearErrorData() {
			this.error = {
				amount: false,
				admin_id: false,
				password: false,
			};
		},

		async createWithdrawRequest() {
			this.clearErrorData();
			if (this.withdraw.amount == null) {
				return (this.error.amount = "Amount is required!");
			}
			if (this.withdraw.admin_id == null) {
				return (this.error.admin_id = "Select an admin!");
			}
			if (this.withdraw.password.trim() == "") {
				return (this.error.password = "Password is required!");
			}
			this.btnLoading = true;
			const res = await this.callApi(
				"post",
				"/app/customer/create/withdraw/request",
				this.withdraw
			);
			if (res.status == 201) {
				let index = this.admins.findIndex(
					(element) => element.id == this.withdraw.admin_id
				);
				res.data.admin = {
					id: this.withdraw.admin_id,
					name: this.admins[index].name,
				};
				this.allWithdrawHistoy.unshift(res.data);
				this.clearData();
				this.ns("Withdraw Requset Added!");
			} else if (res.status == 401) {
				if (res.data.type == "inval-pass") {
					this.error.password = res.data.message;
				}
			} else {
				this.swr();
			}
			this.btnLoading = false;
		},
	},
	async created() {
		this.cardLoading = true;
		const [customers, admins] = await Promise.all([
			this.callApi("get", "/app/customer/get/transaction/withdraw/request/all"),
			this.callApi("get", "/app/customer/get/admins/all"),
		]);
		if (customers.status == 200 && admins.status == 200) {
			this.allWithdrawHistoy = customers.data;
			this.admins = admins.data;
		} else {
			this.nswr();
		}
		this.cardLoading = false;
	},
};
</script>