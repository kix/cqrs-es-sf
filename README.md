# Hexagonal/isolated monolith architecture

Questions:
* What do we want to achieve?
* How do we do that?

#### How do we adjust that with a legacy system?

Of course, newly implemented functionality has to follow these design rules from the start.

Whenever the newly implemented functionality needs to be accessed from the legacy side, the adapters available as
regular Symfony services should accept queries and commands.

#### Why not use Symfony routes?

Symfony routes, when introduced in a distributed application, defeat the purpose of such distribution, since the
knowledge about the mere fact of the existence of the framework is not something we want. 

## The gist of the architecture

We have a 2-axis coordinate grid which consists of layers and domains. There is one domain named `Common` which is 
supposed to support the whole application.

The horizontal layers consist of a common namespace which is meant to keep the whole application intact, and the 
separate per-domain namespaces. The domain separation is based on business requirements. 

The vertical layers of the grid are as follows:

### Application

Application layer defines the abstracted interactions of a domain, such as commands and queries. The classes usually 
present in this layer are commands, queries and their appropriate handlers.

Commands and queries are the lingua franca of the application, and consequently those should be registered in a central 
knowledge base.

### Domain

Domain layer is responsible for the business rules enforced in the application. It contains the aggregates and the 
events that those can accept.

### Infrastructure

Infrastructure layer specifies how we interact with services and technologies external to us, such as databases, 
message queues etc. It can be used to persist data to domain-local storages.

## Practical approach



## Example rundown

Let's imagine this business scenario: we allow people to sell and buy tickets to sports events, concerts and other types
of public activities that usually have an entrance fee.