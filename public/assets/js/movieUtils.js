
const doAjax = async (url, params) => {
    let res = await $.ajax({
        url: url,
        data: params,
    });
    return res;
}


//Funciones para reusar.

class MovieUtils {
    API_KEY = "d19f7d72f38d6f23413e5c84cfcfa305";
    GET_MOVIE_INFO_BY_ID = 'https://api.themoviedb.org/3/movie/__MOVIE_ID__?language=en-US';
    GET_MOVIES = ""

    async getMovieInfo(movieId) {
        let url = this.GET_MOVIE_INFO_BY_ID.replace("__MOVIE_ID__", movieId);
        return await doAjax(url, {});
    }

    renderMovie(movieId) {

    }

    getGenres() {

    }


}