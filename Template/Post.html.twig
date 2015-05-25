<div class="page_block">
			<div class="post_author">Autor: <a href="?page=user&user={{ post.authorid }}&action=show">{{ post.author }}</a></div>
			<div class="post_date"><a href="?action=show&only={{ post.id }}">{{ post.date }}</a></div></br>
	<div class="post_title"><a href="?action=show&only={{ post.id }}"><h2>{{ post.title }}</h2></a></div></br>
	{{ post.text|raw }}</br>
	<div class='post_footer'><a href="?action=editpost&only={{ post.id }}">Edytuj</a>
	<script>function deletes(postid) {
				if(!confirm("Czy na pewno chcesz usunąć ten post?")) {
				} else {
					location.replace("?action=remove&id="+postid);
				}
			}
	</script>
	<a href="javascript:deletes('{{ post.id }}');">Usuń</a></div>
	</br><div style='color: #3a95d1; font-size: 25px;'>Komentarze: </div></br>
	{% if login_panel.user is defined %}
	<script>function check() {
		nick = document.forms["create_com"]["text"].value;
		if(nick == null || nick == "") {
			alert("Napisz coś!");
			return false;
		}
		return true;
	}</script>
	Treść:</br>
	<center><form name="create_com" action="?action=addcom&only={{ post.id }}" method="POST" onsubmit="return check()">
	<textarea class="com_text_edit" name="text" ></textarea></br>
	<input type="hidden" name="id" value="{{ post.id }}">
	<input type="submit" value="Wyślij"/></form></center>
	{% endif %}
	<div id="comments">
	{% for comment in post.comments %}
		<div class='comment'>
		<div class='com_author'>Autor: <a href="?page=user&user={{ comment.author }}&action=show">{{ comment.nick }}</a></div>
		<div class='com_date'>{{ comment.date }}</div></br>
		<div class='com_text'>{{ comment.text|raw }}
		{% if rank == 1 %}
			<div class='com_footer'><script>function delete_com(id) {
				if(!confirm("Czy na pewno chcesz usunąć ten komentarz?")) {
				} else {
					location.replace("?action=removecom&comid={{ comment.id }}&only={{ post.id }}");
				}
			}</script>
			<input type='button' onclick="javascript:delete_com({{ comment.id }});" value='Usuń'>
		{% endif %}
		</div></div>
	{% endfor %}
	</div>
</div>