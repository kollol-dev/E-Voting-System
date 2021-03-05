<template>
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="card card-stats">
					<div class="card-header card-header-warning card-header-icon">
						<div class="card-icon">
							<i class="material-icons">content_copy</i>
						</div>
						<p class="card-category">Total Income</p>
						<h3 class="card-title mb-2">
							<Loader
								style="float: right"
								v-if="tableLoading"
								position="inline"
							/>
							<template v-else>
								৳ {{ profileData.total_income ? profileData.total_income : 0 }}
							</template>
						</h3>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="card card-stats">
					<div class="card-header card-header-warning card-header-icon">
						<div class="card-icon">
							<i class="material-icons">content_copy</i>
						</div>
						<p class="card-category">Welcome Commision</p>
						<h3 class="card-title mb-2">৳ 0.40</h3>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="card card-stats">
					<div class="card-header card-header-success card-header-icon">
						<div class="card-icon">
							<i class="material-icons">store</i>
						</div>
						<p class="card-category">One Link Commision</p>
						<h3 class="card-title mb-2">
							<Loader
								style="float: right"
								v-if="tableLoading"
								position="inline"
							/>
							<template v-else>
								৳
								{{
									profileData.one_link_commision
										? profileData.one_link_commision
										: 0
								}}</template
							>
						</h3>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="card card-stats">
					<div class="card-header card-header-danger card-header-icon">
						<div class="card-icon">
							<i class="material-icons">info_outline</i>
						</div>
						<p class="card-category">Total Sponsore Generation Commision</p>
						<h3 class="card-title mb-2">
							৳
							{{
								profileData.total_sponsor_generation_commision
									? profileData.total_sponsor_generation_commision
									: 0
							}}
						</h3>
					</div>
				</div>
			</div>
			<!-- <div class="col-lg-3 col-md-6 col-sm-6">
				<div class="card card-stats">
					<div class="card-header card-header-info card-header-icon">
						<div class="card-icon">
							<i class="fa fa-twitter"></i>
						</div>
						<p class="card-category">Sponsor Income</p>
						<h3 class="card-title mb-2">৳34,245</h3>
					</div>
				</div>
			</div> -->
		</div>
		<div class="row">
			<div class="col-md-8">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title">Profile Information</h4>
						<!-- <p class="card-category">Complete your profile</p> -->
					</div>
					<div class="card-body">
						<form>
							<div class="row">
								<div class="col-12">
									<div class="form-group">
										<label class="bmd-label-floating">Name:</label>
										<span> {{ authUser.name }}</span>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Mobile:</label>
										<span>{{ authUser.mobile }}</span>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">UID:</label>
										<span>{{ authUser.uid }}</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Sponsored By:</label>
										<span v-if="sponsor"
											>{{ sponsor.name }} ({{ sponsor.uid }})</span
										>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">One Link:</label>
										<span>{{ authUser.country }}</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Nid:</label>
										<span>{{ authUser.nid }}</span>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Country:</label>
										<span>{{ authUser.country }}</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Division:</label>
										<span>{{ authUser.division }}</span>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">District:</label>
										<span>{{ authUser.district }}</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Upazila:</label>
										<span>{{ authUser.upazila }}</span>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">City:</label>
										<span>{{ authUser.city }}</span>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Ward/Union:</label>
										<span>{{ authUser.ward }}</span>
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label class="bmd-label-floating">Area/Village:</label>
										<span>{{ authUser.area }}</span>
									</div>
								</div>
							</div>
							<div class="clearfix"></div>
						</form>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card card-stats">
					<div class="card-header card-header-warning card-header-icon">
						<div class="card-icon">
							<i class="material-icons">content_copy</i>
						</div>
						<p class="card-category">Auto Magic Commision</p>
						<h3 class="card-title mb-2">৳34,245</h3>
					</div>
				</div>
				<div class="card card-stats">
					<div class="card-header card-header-warning card-header-icon">
						<div class="card-icon">
							<i class="material-icons">content_copy</i>
						</div>
						<p class="card-category">Level Commision</p>
						<h3 class="card-title mb-2">৳34,245</h3>
					</div>
				</div>
				<div class="card card-stats">
					<div class="card-header card-header-warning card-header-icon">
						<div class="card-icon">
							<i class="material-icons">content_copy</i>
						</div>
						<p class="card-category">Match Commision</p>
						<h3 class="card-title mb-2">৳34,245</h3>
					</div>
				</div>
				<!-- <div class="card card-profile">
					<div class="card-avatar">
						<a href="javascript:;">
							<img class="img" :src="authUser.profilePic" />
						</a>
					</div>
					<div class="card-body">
						<h4 class="card-title">{{ authUser.name }}</h4>
						<p class="card-description">
							User since {{ authUser.created_at | formatDate }}
						</p>
						<a href="javascript:;" class="btn btn-primary btn-round">Follow</a>
					</div>
				</div> -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title">BIC History</h4>
						<p class="card-category">Here is a subtitle for this table</p>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
								<thead class="text-primary">
									<th>No.</th>
									<th>ID</th>
									<th>Date</th>
									<th>Point</th>
								</thead>
								<tbody>
									<tr v-for="(item, index) in bicData" :key="'bic' + index">
										<td>{{ index + 1 }}</td>
										<td>{{ item.id }}</td>
										<td>{{ item.created_at | formatDateTime }}</td>
										<td class="text-primary">{{ item.amount / 2 }}</td>
									</tr>
								</tbody>
							</table>
							<div class="d-flex justify-content-center" v-if="tableLoading">
								<Loader />
							</div>
							<div
								class="d-flex justify-content-center"
								v-if="!tableLoading && bicData.length == 0"
							>
								<p>No bic history</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-primary">
						<h4 class="card-title">Sponsored UsersHistory</h4>
						<p class="card-category">Here is a subtitle for this table</p>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table">
								<thead class="text-primary">
									<th>No.</th>
									<th>Date</th>
									<th>UID</th>
									<th>Picture</th>
									<th>Customer Name</th>
								</thead>
								<tbody>
									<tr v-for="(item, index) in usersTree" :key="'bic' + index">
										<td>{{ index + 1 }}</td>
										<td>{{ item.created_at | formatDateTime }}</td>
										<td>{{ item.customer.uid }}</td>
										<td>
											<img
												:src="item.customer.profilePic"
												style="width: 30px; height: 30px"
												alt=""
											/>
										</td>
										<td>{{ item.customer.name }}</td>
									</tr>
								</tbody>
							</table>
							<div class="d-flex justify-content-center" v-if="tableLoading">
								<Loader />
							</div>
							<div
								class="d-flex justify-content-center"
								v-if="!tableLoading && bicData.length == 0"
							>
								<p>No sponsor history</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			profileData: {},
			sponsor: {},
			bicData: [],
			usersTree: [],
			tableLoading: true,
		};
	},

	async created() {
		this.tableLoading = true;
		const [profile, res, bic, tree] = await Promise.all([
			this.callApi("get", "/app/customer/get/profile-details"),
			this.callApi("get", "/app/customer/get/sponsor-details"),
			this.callApi("get", "/app/customer/get/bic-details"),
			this.callApi("get", "/app/customer/get/tree-child-details"),
		]);
		if (profile.status == 200) {
			this.profileData = profile.data;
		}
		if (res.status == 200) {
			this.sponsor = res.data;
		}
		if (bic.status == 200) {
			this.bicData = bic.data;
		}
		if (tree.status == 200) {
			this.usersTree = tree.data;
		}
		this.tableLoading = false;
	},
};
</script>