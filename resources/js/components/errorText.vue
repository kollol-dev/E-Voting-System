<template>
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<i class="material-icons">close</i>
		</button>
		<span>
			<b> Oops</b> <br />
			{{ text }}</span
		>
	</div>
</template>
<script>
export default {
	props: ["text"],
};
</script>