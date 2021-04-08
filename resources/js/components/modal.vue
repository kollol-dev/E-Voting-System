<template>
	<Modal
		title="Add Admin"
		v-model="createModal"
		:footer-hide="true"
		:mask-closable="false"
		:closable="false"
	>
		<form v-on:submit.prevent="addAdmin">
			<div class="row">
				<div class="col-12">
					<div class="form-group">
						<label class="bmd-label-floating">Name</label>
						<input v-mode-l="admin.name" type="text" class="form-control" />
						<p v-if="error.name" class="text-danger">*{{ error.name }}</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">Mobile</label>
						<input v-model="admin.mobile" type="text" class="form-control" />
						<p v-if="error.mobile" class="text-danger">*{{ error.mobile }}</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">Country</label>
						<input v-model="admin.country" type="text" class="form-control" />
						<p v-if="error.country" class="text-danger">*{{ error.country }}</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">Division</label>
						<input v-model="admin.division" type="text" class="form-control" />
						<p v-if="error.division" class="text-danger">
							*{{ error.division }}
						</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">District</label>
						<input v-model="admin.district" type="text" class="form-control" />
						<p v-if="error.district" class="text-danger">
							*{{ error.district }}
						</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">Upazilla</label>
						<input v-model="admin.upazila" type="text" class="form-control" />
						<p v-if="error.upazila" class="text-danger">*{{ error.upazila }}</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">City/Union</label>
						<input v-model="admin.city" type="text" class="form-control" />
						<p v-if="error.city" class="text-danger">*{{ error.city }}</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">Area/Village</label>
						<input v-model="admin.area" type="text" class="form-control" />
						<p v-if="error.area" class="text-danger">*{{ error.area }}</p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="bmd-label-floating">Password</label>
						<input
							v-model="admin.password"
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
					<p class=".text-center">Adding</p>
				</template>
				<template v-else>
					<p>Add</p>
				</template>
			</button>
			<button @click="closeModal" class="btn btn-default pull-right">
				<p>Cancel</p>
			</button>
			<div class="clearfix"></div>
		</form>
	</Modal>
</template>