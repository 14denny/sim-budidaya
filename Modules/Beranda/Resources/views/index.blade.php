@extends('master-layout')

@section('title')
	Beranda
@endsection

@section('subtitle')
	Halaman Utama
@endsection

@section('body')
	<div class="card shadow-sm">
		<div class="card-header">
			<h3 class="card-title">Title</h3>
			<div class="card-toolbar">
				<button type="button" class="btn btn-sm btn-light">
					Action
				</button>
			</div>
		</div>
		<div class="card-body">
			Lorem Ipsum is simply dummy text...
		</div>
		<div class="card-footer">
			Footer
		</div>
	</div>
@endsection