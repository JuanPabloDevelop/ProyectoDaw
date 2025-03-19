import {scrollToElementId, parseTipo, colorAleatorio, handleSkeleton } from '../../../js/helper.js'
import handleFetchData from '../../../js/service/services.js'

document.addEventListener("DOMContentLoaded", async () => {

    setTimeout(async () => {
        handleSkeleton();

        const getPosts = await handleFetchData({
            action: "post-get-all",
            filter: "all",
        });

        if(getPosts) {
            setPosts(getPosts);
        } else {
            emptyPosts();
        }
        const filters = await handleFetchData({
            action: "filter-get-posts-types",
        });

        if (filters  && Array.isArray(filters)) {
            const parseFilters = filters.map(item =>parseTipo(item.tipo));
            localStorage.setItem('filters', parseFilters);
            setSelectOptions(filters, 'filter-select');
        }
    }, 2000);

});

export function setSelectOptions(data, id) {
    const select = document.getElementById(id);
    // Borrar duplicados
    const uniqueArray = [...new Set(data)];
    uniqueArray.forEach(item => {
        const option = document.createElement('option');
        option.value = item.tipo;
        option.textContent = parseTipo(item.tipo);
        select.appendChild(option);
    });
    select.addEventListener('change', async (event) => {
        const filters = await handleFetchData(
        {
            action: "post-get-all",
            filter: event.target.value,
        });

        if(filters) {
            setPosts(filters);
        } else {
            emptyPosts();
        }
    }
    );
}

/**  POSTS **/
async function editPost(id) {
    try {
        const response = await handleFetchData({id, action: "post-get-by-id",});
        document.getElementById("modalPostTitle").value = response[0].titulo;
        document.getElementById("modalPostContent").value = response[0].contenido;
        document.getElementById("modalPostId").value = response[0].id_post;
        
        // Cargar las opciones del select
        const select = document.getElementById("modalPostType");
        // Limpiar opciones existentes
        select.innerHTML = '';
        
        // Crear las opciones basadas en los tipos disponibles
        const tipos = {
            "ilu": "Iluminación",
            "mobi": "Mobiliario",
            "text": "Textiles",
            "acc": "Accesorios"
        };

        // Agregar las opciones al select
        Object.entries(tipos).forEach(([valor, texto]) => {
            const option = document.createElement('option');
            option.value = valor;
            option.textContent = texto;
            // Si es el tipo actual del post, seleccionarlo
            if (valor === response[0].tipo) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        const modal = document.getElementById("editPostModal");
        const closeBtn = document.getElementById("close");

        // Función para cerrar el modal
        const closeModal = () => {
            modal.style.display = "none";
        };

        // Event listener para el botón de cierre (×)
        closeBtn.onclick = closeModal;

        // Event listener para cerrar el modal al hacer clic fuera de él
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        };

        // Event listeners para los botones
        document.getElementById("modalPostSave").onclick = async () => {
            const title = document.getElementById("modalPostTitle").value;
            const content = document.getElementById("modalPostContent").value;
            const type = document.getElementById("modalPostType").value;
            const postId = document.getElementById("modalPostId").value;
            
            if (!title || !content) {
                showErrorMessage('Por favor, rellena todos los campos obligatorios.');
                return;
            }

            const postUpdate = await handleFetchData(
                {
                    action: "post-update",
                    id: postId,
                    titulo: title,
                    contenido: content,
                    tipo: type,
                });
        
            if(postUpdate) {
                setPosts(postUpdate);
            }

            closeModal();
        };

        document.getElementById("modalPostCancel").onclick = closeModal;

        // Mostrar el modal
        modal.style.display = "block";
    } catch (error) {
        showErrorMessage(error);
    }
}

async function deletePost(id) {
    const deleteAction = await handleFetchData({
        action: "post-delete",
        id: id,
    });
    if(deleteAction) {
        setPosts(deleteAction);
    }
};
    
async function setPosts(data) {
    try {
        // Primero limpiamos y luego añadimos los posts
        const postsContainer = document.getElementById('posts-container');
        postsContainer.innerHTML = '';

        // Pedimos los comentarios
        const comments = await handleFetchData({ action: "comments-get-all",});

        data.forEach(async (post, index) => {

            // Filtramos  los comentarios
            const commentsFiltered = comments.filter(comment => comment.id_post === post.id_post);

            // Crear card
            const card = document.createElement('div');
            card.classList.add('card');
            card.id = `card-${post.id_post}`;
            const userSession = JSON.parse(localStorage.getItem('responseData'));

            // Crear avatar
            const avatar = document.createElement('div');
            avatar.classList.add('avatar');
            let iniciales = post.nombre.charAt(0) + post.apellidos.charAt(0);
            iniciales = iniciales.toUpperCase();
            avatar.textContent = iniciales;
            avatar.style.backgroundColor = colorAleatorio();
            card.appendChild(avatar);

            // Crear imagen
            const userImg = document.createElement('img');
            userImg.classList.add('image');
            userImg.alt = 'user image';
            userImg.src = `client//assets/users/user-${post.autor_id}.jpg`;
            card.appendChild(userImg);


            // Añadir campos a la card
            Object.entries(post).forEach((value) => {
                const file = document.createElement('p');
                file.classList.add('file');
                file.id = `post-${value[0]}-${index}`;

                if(value[0] === 'fecha_modificacion') {
                    value[1] = value[1] ? value[1] : '';
                }

                if(value[0] === 'tipo') {
                    value[1] = parseTipo(value[1]);
                }

                if(value[0] === 'rol' || value[0] === 'autor_id' || value[0] === 'id_post') {
                    return;
                }

                file.textContent = value[1];
                card.appendChild(file);
            });

            // Mostrar fecha de modificación solo si es distinta a la de creación
            const fechaCreacion = document.getElementById(`post-fecha_creacion-${0}`);
            const fechaModificacion = document.getElementById(`post-fecha_modificacion-${0}`);
            if(fechaCreacion && fechaModificacion) {
                fechaCreacion.textContent === fechaModificacion.textContent ? fechaModificacion.style.display = 'none' : fechaModificacion.style.display = 'block';
            }

            // Añadir número comentarios
            if(commentsFiltered.length > 0) {
                const commentsCounter = document.createElement('p');
                commentsCounter.classList.add('comments-counter');
                commentsCounter.textContent = `${commentsFiltered.length} comentarios`;
                card.appendChild(commentsCounter);
            };

            if(userSession && (userSession.id === post.author_id || userSession.rol === '0')) { 
                // Añadir las acciones
                const actionsCell = document.createElement('div');
                actionsCell.id = 'post-actions-content';
                actionsCell.classList.add('actions');
                const editButton = document.createElement('button');
                editButton.textContent = 'Editar';
                editButton.classList.add('button')
                editButton.onclick = () => editPost(post.id_post);

                const deleteButton = document.createElement('button');
                deleteButton.textContent = 'Eliminar';
                deleteButton.classList.add('button')
                deleteButton.onclick = () => deletePost(post.id_post);

                const commentsButton = document.createElement('button');
                commentsButton.textContent = 'Comentar';
                commentsButton.classList.add('button')
                commentsButton.onclick = () => handleShowComments(post.id_post);

                actionsCell.appendChild(editButton);
                actionsCell.appendChild(deleteButton);
                actionsCell.appendChild(commentsButton);

                card.appendChild(actionsCell);        
            }

            postsContainer.appendChild(card)

            // Añadir comentarios
            const enrichCard = addCommentToCard(card, post.id_post, commentsFiltered);
            postsContainer.appendChild(enrichCard);
        });

    } catch (error) {
        showErrorMessage(error);
    }
}

function emptyPosts() {
    const postsContainer = document.getElementById('posts-container');
    postsContainer.innerHTML = 'No hay registros.';
}

async function updateScrolledPosts(id, comments) {
    const postsContainer = document.getElementById('posts-container');
    const card = document.getElementById(`card-${id}`);
    const enrichCard = addCommentToCard(card, id, comments);
    postsContainer.appendChild(enrichCard);

    // Actualizo los posts
    const getPosts = await handleFetchData(
        {
            action: "post-get-all",
            filter: "all",
        });

    if(getPosts) {
        setPosts(getPosts);
    } else {
        emptyPosts();
    }

    scrollToElementId(`comments-container-${id}`);
}

/**  COMMENTS **/
function addCommentToCard(cardInfo, postId, comments) {
    const card = cardInfo || document.getElementById(`card-${postId}`); 
    debugger;
    const userSession = JSON.parse(localStorage.getItem('responseData'));
    
    const commentsContainer = document.createElement('div');
    commentsContainer.classList.add('comments-container');
    commentsContainer.classList.add('comments-container-close');
    commentsContainer.id = `comments-container-${postId}`;

    const commentsListContainer = document.createElement('div');
    commentsListContainer.classList.add('comments-list-container');
    commentsListContainer.id = `comments-list-container-${postId}`;

    commentsContainer.appendChild(commentsListContainer);
    card.appendChild(commentsContainer);

    comments.forEach(comment => {
        const commentCard = document.createElement('div');
        commentCard.classList.add('comment-card');
        commentCard.id = `comment-card-${comment.id_comment}`;

        const commentContentContainer = document.createElement('div');
        commentContentContainer.classList.add('comment-content-container');

        const commentUserImg = document.createElement('img');
        commentUserImg.classList.add('image');
        commentUserImg.classList.add('image--comentario');
        commentUserImg.alt = 'user image';
        commentUserImg.src = `client//assets/users/user-${comment.id_usuario}.jpg`;
        commentContentContainer.appendChild(commentUserImg);

        const commentContent = document.createElement('p');
        commentContent.classList.add('content');
        commentContent.textContent = comment.contenido;
        commentContentContainer.appendChild(commentContent);

        const creationDateContent = document.createElement('p');
        creationDateContent.classList.add('creation-date');
        creationDateContent.textContent = comment.fecha_creacion;
        commentContentContainer.appendChild(creationDateContent);

        if (comment.fecha_modificacion !== comment.fecha_creacion) {
            const editionDateContent = document.createElement('p');
            editionDateContent.classList.add('edition-date');
            editionDateContent.textContent = comment.fecha_modificacion;
            commentContentContainer.appendChild(editionDateContent);
        }


        commentCard.appendChild(commentContentContainer);

        if(userSession && (userSession.id === comment.id_usuario || userSession.rol === '0')) { 
            // Añadir las acciones
            const commentActionsCell = document.createElement('div');
            commentActionsCell.id = 'comments-actions-content';
            commentActionsCell.classList.add('actions');
            const editCommentButton = document.createElement('button');
            editCommentButton.textContent = 'Editar';
            editCommentButton.classList.add('button')
            editCommentButton.onclick = () => editComment(postId, comment.id_comment);

            const deleteCommentButton = document.createElement('button');
            deleteCommentButton.textContent = 'Eliminar';
            deleteCommentButton.classList.add('button')
            deleteCommentButton.onclick = () => deleteComment(postId,comment.id_comment);

            commentActionsCell.appendChild(editCommentButton);
            commentActionsCell.appendChild(deleteCommentButton);

            commentCard.appendChild(commentActionsCell);        
        }
        commentsListContainer.appendChild(commentCard);
    });

    // Empty comments message
    if(!comments.length) {
        const emptyCommentText = document.createElement('p');
        emptyCommentText.classList.add('comments-empty-text');
        emptyCommentText.textContent = "Sé el primero en comentar";
        commentsListContainer.appendChild(emptyCommentText);
    }

    // Input para añadir comentarios
    const sendCommentContent = document.createElement('div');
    sendCommentContent.id = 'comments-add-content';
    sendCommentContent.classList.add('comments-add-content');

    const commentInput = document.createElement('input');
    commentInput.classList.add('comment-input');
    commentInput.id = 'new-comment-input';
    commentInput.placeholder = 'Escribe un comentario...';
    sendCommentContent.appendChild(commentInput);

    const AddCommentButton = document.createElement('button');
    AddCommentButton.textContent = 'Enviar';
    AddCommentButton.classList.add('button')
    AddCommentButton.onclick = () =>addComment(postId, commentInput.value);
    sendCommentContent.appendChild(AddCommentButton);

    commentsContainer.appendChild(sendCommentContent);

    return card;
};


async function addComment(id_post, content) {
    const userSession = JSON.parse(localStorage.getItem('responseData'));
    document.getElementById('new-comment-input').value = '';
    const updatedComments = await handleFetchData({
        action: "comments-add",
        idPost: id_post,
        content: content,
        userId: userSession.id,
    });

    if (updatedComments) {
        updateScrolledPosts(id_post, updatedComments);
    }
};

async function handleShowComments(id) {

    try {
        const commentsContainer = document.getElementById(`comments-container-${id}`);

        if (commentsContainer) {
            if(commentsContainer.classList.contains('comments-container-close')) {
                commentsContainer.classList.remove('comments-container-close');
                commentsContainer.classList.add('comments-container-open');
            } else {
                commentsContainer.classList.remove('comments-container-open');
                commentsContainer.classList.add('comments-container-close');
            }
        }
        return;

    } catch (error) {
        showErrorMessage(error);
    }
};

async function deleteComment(postId, commentId) {
    const commentsUpdated = await handleFetchData({
        action: "comments-delete",
        id: commentId,
    });

    if(commentsUpdated) {
        updateScrolledPosts(postId, commentsUpdated);
    }
};