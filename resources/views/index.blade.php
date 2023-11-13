@include('header');
<div class="container-fluid">
	<div class="row fh5co-post-entry">
		<?php foreach ($rows as $row) : ?>
			<article class="col-lg-3 col-md-3 col-sm-3 col-xs-6 col-xxs-12 animate-box">
				<a href="{{url('single/'.$row->slag)}}">
					<figure>
						<img src="{{url($row->image)}}" alt="Image" class="img-responsive">
					</figure>
					<span class="fh5co-meta">{{$row->category}}</span>
					<h2 class="fh5co-article-title">{{ucfirst($row->title)}}</h2>
					<span class="fh5co-meta fh5co-date">{{date('F jS, Y',strtotime($row->created_at))}}</span>
				</a>
			</article>
		<?php endforeach; ?>
		<div class="clearfix visible-xs-block"></div>
		@include('pagination')
	</div>
</div>
@include('footer');