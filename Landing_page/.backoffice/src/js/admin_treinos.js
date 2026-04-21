$(function(){

    // Quando escrever o link do YouTube → criar preview
    $("#video_url").on("input", function () {
        const url = $(this).val().trim();
        const videoID = extrairVideoID(url);

        if (!videoID) {
            $("#videoPreview").addClass("d-none");
            $("#thumbPreview").addClass("d-none");
            return;
        }

        // gerar embed
        const embed = "https://www.youtube.com/embed/" + videoID;

        $("#iframeVideo").attr("src", embed);
        $("#videoPreview").removeClass("d-none");

        // gerar thumbnail
        const thumb = "https://img.youtube.com/vi/" + videoID + "/maxresdefault.jpg";
        $("#imgThumb").attr("src", thumb);
        $("#thumbPreview").removeClass("d-none");
    });


    // SUBMETER FORMULÁRIO
    $("#formNovoTreino").on("submit", function(e){
        e.preventDefault();

        let dados = new FormData(this);
        dados.append("op", 1);

        // thumbnail gerada automaticamente
        const videoID = extrairVideoID($("#video_url").val());
        if (videoID) {
            dados.append("thumbnail", "https://img.youtube.com/vi/" + videoID + "/maxresdefault.jpg");
        }

        $.ajax({
            url: "src/controller/controllerTreino.php",
            method: "POST",
            data: dados,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(resp){
            if (resp.flag) {
                Swal.fire("Sucesso", "Treino criado com sucesso!", "success");
                $("#formNovoTreino")[0].reset();
                $("#videoPreview").addClass("d-none");
                $("#thumbPreview").addClass("d-none");
            } else {
                Swal.fire("Erro", resp.msg, "error");
            }
        })
        .fail(function(){
            Swal.fire("Erro", "Falha de comunicação com o servidor.", "error");
        });

    });

});


// Função para extrair ID do vídeo do YouTube
function extrairVideoID(url){
    try {
        const reg = /(?:v=|\.be\/)([^&\n?#]+)/;
        const match = url.match(reg);
        return match ? match[1] : null;
    } catch(e){
        return null;
    }
}
