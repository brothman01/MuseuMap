import React, { Component } from 'react';

class App extends React.Component {

  constructor( props ) {
    super( props );
    this.state = {
      posts: []
    };
  }


  showMap = () => {

    // declare the state variable as a constant
    const { posts } = this.state;

    return '| A map |';

  }

  render() {
    return (
      <div>
        {this.showMap()}
      </div>
    );
  }
}

export default App;