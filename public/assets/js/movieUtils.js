
const doAjax = async (url, params) => {
    let res = await $.ajax({
        url: url,
        data: params,
    });
    return res;
}


//Funciones para reusar.

class MovieUtils {
    API_KEY = "eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJkMTlmN2Q3MmYzOGQ2ZjIzNDEzZTVjODRjZmNmYTMwNSIsInN1YiI6IjY0ZGJhZGM5Yjc3ZDRiMTE0MjVkZmEwNyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.OE2bMBkUtaosNS2qL0Sik6tci6W5dGw3AtQFcpH-Elg";
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